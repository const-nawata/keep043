// General JS Functions

//  Tamlate line. DO NOT DELETE!!!
//    if (isIE()&&window.event.keyCode==9){setFocus(firstElId);return false;}else if(event.keyCode==9){setFocus(firstElId);return false;}

var tst_choice	= true;
function tstHandler( id ){
	var color	= ( tst_choice ) ? "#FF9999" : "#99FFFF";
	tst_choice	= !tst_choice;
	document.getElementById( id ).style.backgroundColor = color;
}
//______________________________________________________________________________

function isIE(){ return ( navigator.appName == "Microsoft Internet Explorer" ); }
//______________________________________________________________________________

//TODO: Implement for IE8
function setFocus( id ){ setTimeout( 'document.getElementById( \"'+id+'\" ).focus();', 50 ); }
//______________________________________________________________________________

function setTabSectionCssClassName( TabCode, Section, Prefix ){
	var tag_id		= TabCode+Section + "TagId";
	var class_name	= Prefix + Section;
	document.getElementById( tag_id ).className	= class_name;
}
//______________________________________________________________________________

var current_tab_code	= '';
function mouseOverOutTab( TabCode, Prefix ){
	if( TabCode != current_tab_code ){		//	This fuckin `if` is necessary for Safari and Google Chrome
		setTabSectionCssClassName( TabCode, _TAB_LEFT_IMG_SFX, Prefix );
		setTabSectionCssClassName( TabCode, _TAB_CENTER_IMG_SFX, Prefix );
		setTabSectionCssClassName( TabCode, _TAB_RIGHT_IMG_SFX, Prefix );
	}
}
//______________________________________________________________________________

var out_css_cl;
function setMouseOverCss( obj, cssCl ){
	out_css_cl		= obj.className;
	obj.className	= cssCl;
}
//______________________________________________________________________________

function setMouseOutCss( obj ){
	obj.className	= out_css_cl;
}
//______________________________________________________________________________

function changeSettTab( obj ){
//	var prev_obj	= document.getElementById( "prev_sell_id" );
	var new_id		= "" + obj.id;
	var new_num		= new_id.substr( 9 );
	
alert( "new_num = " + new_num );
	
	
}
//______________________________________________________________________________
