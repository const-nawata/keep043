<?php
define( '_PPSK_CIPHER_BASE', "ABCDEFGHIJKLMNOPQRSTUVWXYZ:_abcdefghijklmnopqrstuvwxyz0123456789.-" );

class sipherManager{
	private $mBase;
	private $mKey;

	/**
	 * gets sipher key
	 * @access	public
	 * @param	string $cutStr	- string from which cipher key is cut. $cutStr length must be >= 30 symbols.
	 * @return	string
	 */
	public function getSipherKey( $cutStr ){
		$start	= rand( 0, 20 );
		$end	= rand( ( $start + 1 ), ( $start + 10 ) );
		return substr( $cutStr, $start, $end );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets sipher base
	 * @access public
	 * @return string
	 */
	public function getSipherBase(){
		$offset	= rand( 5, 25 );
		return substr( _PPSK_CIPHER_BASE, $offset ).substr( _PPSK_CIPHER_BASE, 0, $offset );
	}

	/**
	 * performs encryption of scring according to key value.
	 * @param string $value to encode. Must contain only letters, digits and symbol `:`
	 * @return string encoded value
	 */
	public function encipherString( $value ){
		$base	= &$this->mBase;
		$key	= &$this->mKey;

		$val_lng	= strlen( $value );
		$key_lng	= strlen( $key );
		$key_pos	= 0; $e_str		= "";
		for( $i	= 0; $i < $val_lng; $i++ ){
			$ch		= substr( $value, $i, 1 );
			$pos	= strpos( $base, $ch );
			$ch_ln	= substr( $base, $pos ).substr( $base, 0, $pos );
			( $key_pos == $key_lng ) ? $key_pos = 0:'';
			$key_ch	= substr( $key, $key_pos, 1 );
			$pos	= strpos( $base, $key_ch );
			$ch		= substr( $ch_ln, $pos, 1 );
			$e_str .= $ch;
			$key_pos++;
		}
		return $e_str;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * performs decoding of scring according to key value.
	 * @param string $value to decode. Must contain only letters, digits and symbol `:`
	 * @return string decoded value
	 */
	public function decipherString( $value ){
		$base	= &$this->mBase;
		$key	= &$this->mKey;

		$val_lng	= strlen( $value );
		$key_lng	= strlen( $key );
		$key_pos	= 0; $e_str		= "";
		for( $i	= 0; $i < $val_lng; $i++ ){
			( $key_pos == $key_lng ) ? $key_pos = 0:'';
			$key_ch	= substr( $key, $key_pos, 1 );
			$pos	= strpos( $base, $key_ch );
			$ch_ln	= substr( $base, $pos ).substr( $base, 0, $pos );
			$ch		= substr( $value, $i, 1 );
			$pos	= strpos( $ch_ln, $ch );
			$new_ch = substr( $base, $pos, 1 );
			$e_str .= $new_ch;
			$key_pos++;
		}
		return $e_str;
	}
	//--------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------

	/**
	 * Constructor
	 * @access public
	 * @param string $base
	 * @param string $key
	 * @return void
	 */
	public function __construct( $base = _EMPTY, $key = _EMPTY ){
		$this->mBase	= $base;
		$this->mKey		= $key;
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
	}
	//--------------------------------------------------------------------------------------------------
}//	Class end																				#######


class gradientManager{
	private $mGrad;
	private $mColor;

	private function decToHexForClr( $val ){
		$new_val	= dechex( intval( $val ) );
		( strlen( $new_val ) == 1 ) ? $new_val = "0".$new_val:'';
		return $new_val;
	}
	//--------------------------------------------------------------------------------------------------

	private function convertRgbToNorm(){
		$pos		= stripos( $this->mColor, '(' );
		$clr_str	= substr( $this->mColor, $pos + 1 );
		$len		= strlen( $clr_str ) - 1;
		$clr_str	= substr( $clr_str, 0, $len );
		list( $r, $g, $b )	= explode( ",", $clr_str );
		return $this->decToHexForClr( $r ).$this->decToHexForClr( $g ).$this->decToHexForClr( $b );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * converts color which was presented in RGB mode to HSL presentation.
	 * @param string $color	- value of color in format NNNNNN
	 * @return array(
	 * 	[h]
	 * 	[s]
	 * 	[l]
	 * )
	 * */
	public function RgbToHsl( $color ){
		$r	= hexdec( substr( $color, 0, 2 ) ) / 255;
		$g	= hexdec( substr( $color, 2, 2 ) ) / 255;
		$b	= hexdec( substr( $color, 4, 2 ) ) / 255;

		$clr_min	= min( $r, $g, $b );
		$clr_max	= max( $r, $g, $b );
		$dlt		= $clr_max - $clr_min;

		$l = ( $clr_max + $clr_min ) / 2;

		if( $clr_max == $clr_min ){
			$h = $s = 0;
		}else{
			$s	= ( 0.5 > $l )
			? $dlt / ( $clr_max + $clr_min )
			: $dlt / ( 2 - $clr_max - $clr_min );

			$d_r = ( ( ( $clr_max - $r ) / 6 ) + ( $dlt / 2 ) ) / $dlt;
			$d_g = ( ( ( $clr_max - $g ) / 6 ) + ( $dlt / 2 ) ) / $dlt;
			$d_b = ( ( ( $clr_max - $b ) / 6 ) + ( $dlt / 2 ) ) / $dlt;

			switch( $clr_max ){
				case $r: $h = $d_b - $d_g; break;
				case $g: $h = ( 1 / 3 ) + $d_r - $d_b; break;
				case $b: $h = ( 2 / 3 ) + $d_g - $d_r; break;
			}

			( 0 > $h ) ? $h += 1:'';
			( 1 < $h ) ? $h -= 1:'';
		}
		return array('h'=>$h, 's'=>$s, 'l'=>$l);
	}
	//--------------------------------------------------------------------------------------------------

	private function Norm_c_t( $c_t ){
		if( $c_t < 0 ){ return ( $c_t + 1 ); }
		if( $c_t > 1 ){ return ( $c_t - 1 ); }
		return $c_t;
	}
	//--------------------------------------------------------------------------------------------------

	private function getDecClr( $c_t, $t1, $t2 ){
		$dlt	= $t2 - $t1;
		if( ( 6 * $c_t ) < 1 ){ return ( $t1 + $dlt * 6 * $c_t ); }
		if( ( 2 * $c_t ) < 1 ){ return ( $t2 ); }
		if( ( 3 * $c_t ) < 2 ){ return ( $t1 + $dlt * ( 2 / 3 - $c_t ) * 6 ); }
		return $t1;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * coverts color which was presented in  HSL mode to RGB presentation.
	 * @param array $clr(	- color presentation in HSL mode
	 * 	[h]
	 * 	[s]
	 * 	[l]
	 * )
	 * @return string color value in format NNNNNN
	 * */
	public function HslToRgb( $hsl ){
		$h	= &$hsl[ 'h' ];
		$s	= &$hsl[ 's' ];
		$l	= &$hsl[ 'l' ];

		if( $s == 0 ){
			$r = $g = $b = $l;
		}else{
			$t2	= ( $l < 0.5 )
			? $l * ( 1 + $s )
			: $l + $s - $l * $s;

			$t1	= 2 * $l - $t2;

			$r_t	= $h + 1 / 3;	$r_t	= self::Norm_c_t( $r_t );
			$g_t	= $h;			$g_t	= self::Norm_c_t( $g_t );
			$b_t	= $h - 1 / 3;	$b_t	= self::Norm_c_t( $b_t );

			$r	= self::getDecClr( $r_t, $t1, $t2);
			$g	= self::getDecClr( $g_t, $t1, $t2);
			$b	= self::getDecClr( $b_t, $t1, $t2);
		}
		$r	= self::decToHexForClr( round( $r * 255 ) );
		$g	= self::decToHexForClr( round( $g * 255 ) );
		$b	= self::decToHexForClr( round( $b * 255 ) );

		return $r.$g.$b;
	}
	//--------------------------------------------------------------------------------------------------

	private function getGrad( $normColor ){
		$hsl = self::RgbToHsl( $normColor );

		if( $hsl[ 's' ] != 0 ){
			$this->mGrad	= $this->mGrad / 1.5;
			( $hsl[ 's' ] < 0.5 ) ? $hsl[ 's' ] += $this->mGrad : $hsl[ 's' ] -= $this->mGrad;
		}
		( $hsl[ 'l' ] < 0.5 ) ? $hsl[ 'l' ] += $this->mGrad : $hsl[ 'l' ] -= $this->mGrad;

		return '#'.self::HslToRgb( $hsl );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 *
	 * @param string $color - color in format `#NNNNNN` or rgb(r, g, b)
	 * @return string  - color in format `#NNNNNN`
	 */
	public function getGradientedColor( $color ){
		$this->mColor	= $color;
		$norm_color		= ( substr_count( $this->mColor, '#' ) )
		? substr( $this->mColor, 1 )
		: $this->convertRgbToNorm();

		return $this->getGrad( $norm_color );;
	}
	//--------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------

	/**
	 * Constructor
	 * @access public
	 * @param integert $grad - gradient value.
	 * @return void
	 */
	public function __construct( $grad = 0.05 ){
		$this->mGrad	= $grad;
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
	}
	//--------------------------------------------------------------------------------------------------
}//	Class end																				#######
?>