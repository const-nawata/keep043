//  VCL scripts
function getBrowserType(){
    var name		= navigator.appName;
    var vers		= navigator.appVersion;
    var maskIE6		= /MSIE 6.0/;
    var maskIE7		= /MSIE 7.0/;
    var maskIE8		= /MSIE 8.0/;
    var maskSafari	= /Safari/;

    if( name == "Microsoft Internet Explorer" ){
         if( vers.match( maskIE6 ) ){ return 'IE6'; }
         if( vers.match( maskIE7 ) ){ return 'IE7'; }
         if( vers.match( maskIE8 ) ){ return 'IE8'; }
    }else if( name == 'Opera' ){
        return 'OP';
    }else{
         if( vers.match( maskSafari ) ){ return 'SF'; }
         else { return 'FF'; }
    }
}
//--------------------------------------------------------------------------------------------------


var colors;
var ids;

function isIdExists( id ){
	for( var i = 0; i < ids.length; i++ ){
		if( ids[ i ] == id ){ return true; }
	}
	return false;
}
//--------------------------------------------------------------------------------------------------

function PPSK_tblLineOver( lineObj ){
	var childItem
		,childObj
		,grad_obj
		;

	grad_obj	= new gradientManager( 0.15 );

	colors	= [];
	ids		= [];
	var idd; var dv_obj; var sell_color;

	childItem	= 0;
	for( childItem in lineObj.childNodes ){
		childObj	= lineObj.childNodes[ childItem ];
		idd		= "" + childObj.id;
		idd			= "dv" + idd.substr( 2 );

		if( childObj.nodeType == 1 &&  childObj.tagName == 'TD' && !isIdExists( idd ) ){
			dv_obj		= document.getElementById( idd );
			sell_color	= dv_obj.style.backgroundColor;
			colors.push( sell_color );
			ids.push( dv_obj.id );
			dv_obj.style.backgroundColor	= grad_obj.getGradientedColor( sell_color );;
		}
	}
	grad_obj	= null;
}
//--------------------------------------------------------------------------------------------------

function PPSK_tblLineOut( lineObj ){
	for( var i = 0; i < ids.length; i++ ){
		document.getElementById( ids[ i ] ).style.backgroundColor	= colors[ i ];
	}
	colors	= null; ids		= null;
}
//--------------------------------------------------------------------------------------------------

function gradientManager( grad ){
	var self	= this;
	this.color;
	this.grad	= grad;

	function decToClrHex( val ){
		var new_val	= parseInt( val );
		new_val	= new_val.toString( 16 );
		( new_val.length == 1 ) ? new_val = "0" + new_val:'';
		return new_val;
	}
	//-----------------------------------------------------

	function convertRgbToNorm(){
		var pos1	= self.color.search( /\(/i );
		var clr_str	= self.color.substr( pos1 + 1 );
		var len		= clr_str.length - 1;
		clr_str		= clr_str.substr( 0, len );
		var tints	= clr_str.split( "," );

		return	decToClrHex( tints[ 0 ] ) +
				decToClrHex( tints[ 1 ] ) +
				decToClrHex( tints[ 2 ] );
	}
	//-----------------------------------------------------

	function getNormClr(){
		var typ	= self.color.search( 'rgb' );
		var ret_val;
		if( typ < 0 ){
			ret_val	= self.color.substr( 1 );
		}else{
			ret_val	= convertRgbToNorm();
		}
		return ret_val;
	}
	//-----------------------------------------------------

	this.RgbToHsl = function( normColor ){
		var h, s
		,d_r ,d_g ,d_b
		,r,g,b
		,clr_min, clr_max
		,dlt, l
		;

		r	= parseInt( normColor.substr( 0, 2 ), 16) / 255;
		g	= parseInt( normColor.substr( 2, 2 ), 16) / 255;
		b	= parseInt( normColor.substr( 4, 2 ), 16) / 255;

		clr_min	= Math.min( r, g, b );
		clr_max	= Math.max( r, g, b );
		dlt		= clr_max - clr_min;

		l = ( clr_max + clr_min ) / 2;

		if( clr_max == clr_min ){
			h = 0; s = 0;
		}else{
			s	= ( 0.5 > l )
				? dlt / ( clr_max + clr_min )
				: dlt / ( 2 - clr_max - clr_min );

			d_r = ( ( ( clr_max - r ) / 6 ) + ( dlt / 2 ) ) / dlt;
			d_g = ( ( ( clr_max - g ) / 6 ) + ( dlt / 2 ) ) / dlt;
			d_b = ( ( ( clr_max - b ) / 6 ) + ( dlt / 2 ) ) / dlt;

			switch( clr_max ){
				case r: h = d_b - d_g; break;
				case g: h = ( 1 / 3 ) + d_r - d_b; break;
				case b: h = ( 2 / 3 ) + d_g - d_r; break;
				default: h = 0;
			}
			h = ( 0 > h ) ? ( h + 1 ) : h;
			h = ( 1 < h ) ? (h - 1):h;
		}
		return { "h": h, "s": s, "l": l };
	};
	//-----------------------------------------------------

	function  Norm_c_t( c_t ){
		if( c_t < 0 ){ return ( c_t + 1 ); }
		if( c_t > 1 ){ return ( c_t - 1 ); }
		return c_t;
	}
	//-----------------------------------------------------

	function getDecClr( c_t, t1, t2 ){
		var dlt	= t2 - t1;
		if( ( 6 * c_t ) < 1 ){ return ( t1 + dlt * 6 * c_t ); }
		if( ( 2 * c_t ) < 1 ){ return ( t2 ); }
		if( ( 3 * c_t ) < 2 ){ return ( t1 + dlt * ( 2 / 3 - c_t ) * 6 ); }
		return t1;
	}
	//-----------------------------------------------------

	this.HslToRgb = function( hsl ){
		var r, g, b
		,t1 ,t2
		;

		if( hsl[ 's' ] == 0 ){
			r	= hsl[ 'l' ];
			g	= hsl[ 'l' ];
			b	= hsl[ 'l' ];
		}else{
			t2	= ( hsl[ 'l' ] < 0.5 )
				? hsl[ 'l' ] * ( 1 + hsl[ 's' ] )
				: hsl[ 'l' ] + hsl[ 's' ] - hsl[ 'l' ] * hsl[ 's' ];

			t1	= 2 * hsl[ 'l' ] - t2;

			r	= getDecClr( Norm_c_t( hsl[ 'h' ] + 1 / 3 ), t1, t2 );
			g	= getDecClr( Norm_c_t( hsl[ 'h' ] ), t1, t2 );
			b	= getDecClr( Norm_c_t( hsl[ 'h' ] - 1 / 3 ), t1, t2 );
		}
		return	decToClrHex( Math.round( r * 255 ) ) +
				decToClrHex( Math.round( g * 255 ) ) +
				decToClrHex( Math.round( b * 255 ) );
	};
	//-----------------------------------------------------

	function getGrad( normColor ){
		var hsl = self.RgbToHsl( normColor );

		if( hsl[ 's' ] != 0 ){
			var grdd	= self.grad / 1.5;
			( hsl[ 's' ] < 0.5 ) ? hsl[ 's' ] += grdd : hsl[ 's' ] -= grdd;
		}
		( hsl[ 'l' ] < 0.5 ) ? hsl[ 'l' ] += self.grad : hsl[ 'l' ] -= self.grad;
		return '#' + self.HslToRgb( hsl );
	}
	//-----------------------------------------------------

	this.getGradientedColor	= function( color ){
		this.color	= color.toLowerCase();
		var norm_color	= getNormClr();
		var grad_color	= getGrad( norm_color );
		return grad_color;
	};
	//-----------------------------------------------------
}
//--------------------------------------------------------------------------------------------------

function setElementEnabled( id, css_cls, handlers ){
	var el_obj	= document.getElementById( id );
	el_obj.className	= css_cls;
	el_obj.disabled = false;
}
//--------------------------------------------------------------------------------------------------

function setElementDisabled( id, css_cls ){
	var el_obj	= document.getElementById( id );
	el_obj.className	= css_cls;
	el_obj.disabled = true;
}
//--------------------------------------------------------------------------------------------------

function removeElement( id ){
	var Node1, len;

	Node1	= document.getElementById( "body_id" );
	len		= Node1.childNodes.length;

	for( var i = 0; i < len; i++ ){
		if( Node1.childNodes[i] && Node1.childNodes[i].id == id ){
			Node1.removeChild(Node1.childNodes[ i ] );
			break;
		}
	}
}
//-------------------------------------------------------------------------------------------------- body_id

function prependDiv( parentId, childId, childCss ){
  var node = document.getElementById( parentId );
  var first = node.firstChild;
  var newNode = document.createElement( 'div' );
  newNode.id	= childId;
  newNode.className	= childCss;
  node.insertBefore( newNode, first );
  return newNode;
}
//--------------------------------------------------------------------------------------------------

function mouseOverOut( obj, cssClass ){
	if( !obj.disabled ){
		obj.className	= cssClass;
	}
}
//--------------------------------------------------------------------------------------------------

//function mouseOut( obj, cssClass ){
//
//}
//--------------------------------------------------------------------------------------------------
