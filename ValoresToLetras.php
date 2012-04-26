<?php
/**
 * Clase que permite convertir valores numéricos en letras.
 *
 * @author Andrés Méndez Juanias <amj.desarrollo@gmail.com>
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
            array("TRES ", "TREINTA ", "TRESCIENTOS "),
            array("CUATRO ", "CUARENTA ", "CUATROCIENTOS "),
            array("CINCO ", "CINCUENTA ", "QUINIENTOS "),
            array("SEIS ", "SESENTA ", "SEISCIENTOS "),
            array("SIETE ", "SETENTA ", "SETECIENTOS "),
            array("OCHO ", "OCHENTA ", "OCHOSIENTOS "),
            array("NUEVE ", "NOVENTA ", "NOVECIENTOS "),
            
        );
    
    private $unidades = array(" ", "MIL ", "MILLON ", "MIL ", "BILLON ", "MIL ", "TRILLON ");
    
    private $text = "";
    
    private $nCount = 0;
    
    /**
     * Constructor de la clase que recibe el número que se desea convertir.
     * @param Int $numero Número que se desea convertir a letras.
     */
    public function __construct($numero) {
                
        $formatoNumero = number_format($numero, 0, '', '.');
        $arrayNumero = explode(".", $formatoNumero);                        
        $this->nCount = count($arrayNumero);
        foreach($arrayNumero as $key => $subNum){ 
            $this->leerCentenas($subNum);
            $this->agregarCuantificador( ($this->nCount-1-$key) , $subNum);
        }        
    }      
    
    /**
     * Método que permite convertir las centenas.
     * @param Int $num Número de 3 dígitos para convertir.
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
     * Método que permite convertir las decenas.
     * @param type $num Número de 2 dígitos para convertir
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
                    $tmpText = in_array($num, array(30, 40, 50, 60, 70, 80, 90))? $contexto[self::$DECENAS]: $contexto[self::$DECENAS]."Y ";
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
     * Método que permite convertir las unidades.
     * @param type $num Número de 1 digito para convertir.     
     */
    private function leerUnidad($num){
        $contexto = $this->numeros[$num];
        $this->text .= $contexto[self::$UNIDADES];        
    }
    
    /**
     * Agrega el cuantificador de las unidades según la posición.
     * @param type $idx Posición del cuantificador.
     * @param type $num Número sobre el cual aplica el cuantificador.
     */
    private function agregarCuantificador($idx, $num){         
        if($idx == 0){
            $this->text .= $this->unidades[$idx];
        }else if( $idx%2 == 0 ){ 
            $tmpText = $num == 1? $this->unidades[$idx]: trim($this->unidades[$idx])."ES ";
            $this->text .= $tmpText;           
        }else{ 
            if($num != 0)
                $num == 1 && $this->nCount == ($idx + 1)? $this->text = $this->unidades[$idx]: $this->text .= $this->unidades[$idx];            
        }
    }
    
    /**
     * Retorna el número convertido en letras.
     * @param String $formato Formato con el cual se desea retornar numero en letras, las opciones son:
     * <pre>
     * +------------------------------------------------------------------------------------------------------------------------------------------------+
     * |    'U'  |   Letras en mayuscula, el equivalente a aplicar la función <b>strtoupper()</b> de PHP.                                                 |
     * |    'L'  |   Letras en minuscula, el equivalente a aplicar la función <b>strtolower()</b> de PHP.                                                 |
     * |    'UC' |   La primera letra de cada palabra en mayuscula y el resto en minuscula, el equivalente a aplicar la función <b>ucwords()</b> de PHP.  | 
     * |    'UF' |   La primera letra del texto mayuscula y el resto en minuscula, el equivalente a aplicar la función <b>ucfirst()</b> de PHP.           |
     * +------------------------------------------------------------------------------------------------------------------------------------------------+
     * </pre>
     * @return string Retorna el número convertido en letras y con el formato aplicado. 
     */
    public function getNumberText($formato = "U") {        
        $text = "";
        switch (strtoupper($formato)) {
            case 'U':
                $text = strtoupper($this->text);
                break;
            case 'L':
                $text = strtolower($this->text);
                break;
            case 'UC':
                $text = ucwords(strtolower($this->text));
                break;
            case 'UF':
                $text = ucfirst(strtolower($this->text)); 
                break;            
            default:
                $text = $this->text;
                break;            
        }         
        return trim($text);
    }    
    
    /**
     * Método estática que retorna el valor en letras del número pasado por parámetro.
     * @param Int $numero Numero el cual se pasara a letras.
     * @param Char $formato Formato de salida del texto.
     * @return String Retorna el número convertido en letras y con el formato aplicado. 
     */
    public static function convertirNumero($numero, $formato = 'U'){        
        $object = new ValoresToLetras($numero);
        return $object->getNumberText($formato);
    }
    
}