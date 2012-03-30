<?php
/**
 * Clase que permite convertir valores numéricos en letras.
 *
 * @author Andres Mendez Juanias <amj.desarrollo@gmail.com>
 * 
 */
class ValoresToLetras {
    
    private static $UNIDADES    = 0;
    private static $DECENAS     = 1;
    private static $CENTENAS    = 2;
    
    private $numeros = 
        Array(
            array(""),
            array("UN ", array( 10 => "DIEZ ", 11 => "ONCE ", 12 => "DOCE ", 13 => "TRECE ", 14 => "CATORCE ", 15 => "QUINCE ", 0 => "DIECI"), array("CIEN", "CIENTO ") ),
            array("DOS ", array( 20 => "VEINTE", 0 => "VEINTI"), "DOSCIENTOS "),
            array("TRES ", "TREINTA", "TRESCIENTOS "),
            array("CUATRO ", "CUARENTA", "CUATROCIENTOS "),
            array("CINCO ", "CINCUENTA", "QUINIENTOS "),
            array("SEIS ", "SESENTA", "SEISCIENTOS "),
            array("SIETE ", "SETENTA", "SETECIENTOS "),
            array("OCHO ", "OCHENTA", "OCHOSIENTOS "),
            array("NUEVE ", "NOVENTA", "NOVECIENTOS "),
            
        );
    
    private $unidades = array(" ", "MIL ", "MILLON ", "MIL ", "BILLON ", "MIL ", "TRILLON ");
    
    private $text = "";
    
    /**
     * Constructor de la clase que recibe el numero que se desea convertir.
     * @param Int $numero Número que se desea convertir a letras.
     */
    public function __construct($numero) {
        
        $strlen = strlen($numero);
        $iteraciones = ceil( ($strlen/3) );
        $numRev = strrev($numero);

        $divNum = array();
        for($idx = 0; $idx < $iteraciones; $idx++){
            $divNum[] = strrev(substr($numRev, ($idx*3), 3));            
        }
        $realNum = array_reverse($divNum);
        foreach($realNum as $key => $subNum){ 
            $this->leerCentenas($subNum);
            $this->agregarCuantificador( (count($realNum)-1-$key) , $subNum);
        }        
    }      
    
    /**
     * Funcion que permite convertir las centenas.
     * @param Int $num Numero de 3 digitos para convertir.
     */
    private function leerCentenas($num) {
        $nLen = strlen($num);
        if($nLen == 3){
            $centena = (int)substr($num, 0, 1);        
            $decena  = (int)substr($num, 1, 2);
            if($centena != 0){
                $contexto = $this->numeros[$centena];
                if($num == 100){
                    $this->text .= "".$contexto[self::$CENTENAS][0];
                }else{            
                    $tmpText = is_array($contexto[self::$CENTENAS])? $contexto[self::$CENTENAS][1]: $contexto[self::$CENTENAS];            
                    $this->text .= "".$tmpText;
                }                
            }                
        }else{
            $decena = $num;
        }                
        $this->leerDecenas($decena);        
    }
    
    /**
     * Funcion que permite convertir las decenas.
     * @param type $num Numero de 2 digitos para convertir
     */
    private function leerDecenas($num) {
        $indLectura = TRUE;
        $nLen = strlen($num);                        
        if($nLen == 2){
            $decena  = (int)substr($num, 0, 1);
            $unidad  = (int)substr($num, 1, 1);
            $contexto = $this->numeros[$decena];
            if($decena != 0){
                if($decena == 1){
                    if(in_array($num, array(10, 11, 12, 13, 14, 15))){                
                        $this->text .= $contexto[self::$DECENAS][$num];
                        $indLectura = FALSE;
                    }else{
                        $this->text .= $contexto[self::$DECENAS][0];
                    }
                }else if($decena == 2){
                    if($num == 20 ){
                        $this->text .= $contexto[self::$DECENAS][$num];
                    }else{
                        $this->text .= $contexto[self::$DECENAS][0];
                    }
                }else{            
                    $tmpText = in_array($num, array(30, 40, 50, 60, 70, 80, 90))? $contexto[self::$DECENAS]: $contexto[self::$DECENAS]." Y ";
                    $this->text .= $tmpText;            
                }        
            }        
        }else{
            $unidad = $num;
        }        
        if($indLectura)
            $this->leerUnidad($unidad);
    }       
    
    /**
     * Funcion que permite convertir las unidades.
     * @param type $num Numero de 1 digito para convertir.     
     */
    private function leerUnidad($num){
        $contexto = $this->numeros[$num];
        $this->text .= $contexto[self::$UNIDADES];        
    }
    
    /**
     * Agrega el cuantificador de la unidades según la posición.
     * @param type $idx Posicion del cuantificador.
     * @param type $num Numero sobre el cual se opera el cuantificador.
     */
    private function agregarCuantificador($idx, $num){         
        if($idx == 0){
            $this->text .= $this->unidades[$idx];
        }else if( $idx%2 == 0 ){            
            $tmpText = $num == 1? $this->unidades[$idx]: trim($this->unidades[$idx])."ES ";
            $this->text .= $tmpText;           
        }else{
            $num == 1? $this->text = $this->unidades[$idx]: $this->text .= $this->unidades[$idx];            
        }
    }
    
    /**
     * Retorna numero en letras.
     * @return string Retorna el número convertido en letras. 
     */
    public function getNumberText() {
        return $this->text;
    }
    
}