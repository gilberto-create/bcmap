////
//
//	Generic Library with jQuery.
//
//	Copyright (C) 2013 Generic Library. All Rights Reserved.
//	
//	Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
//	and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
//
//	Built on top of the jQuery library
//	http://jquery.com
//
////



/*	オーバーライト
==================================================*/

$.fn.hasClassRegExp = function(regex)
{
	var classes = $(this).attr('class');
	 
	if(!classes || !regex) return false;
	 
	classes = classes.split(' ');
	 
	for(var i=0, len=classes.length; i<len; i++)
		if(classes[i].match(regex)) return true;
	 
	return false;
}; 



/*	初期設定
==================================================*/

var gl_general_prefix = 'gl_';
var gl_general_options = {
	
		//	キャンセルクラス
		disable_class		: 'disable',
	
		//	リスト
		list_first		: 'first',
		list_last		: 'last',
		list_item		: 'item',
		odd				: 'odd',
		even			: 'even',
		group			: 'group',
		group_first		: 'group_first',
		group_last		: 'group_last',
	
		//	マウスオーバー関連
		image_hover_class	: 'btn',
		image_hover_suffix	: '_on',
		
		//	時間
		animation_fade		: 1000,
		auto_animation_delay: 5000,
		hover_duration		: 100,
		scroll_duration		: 250,
		
		//	透明度
		opacity				: 0.75,
		
		//	動作関連
		animation_easing	: 'swing',
		
		//	デバッグ
		debug		: false
	}



/*	IE7判定
==================================================*/

function ie7() {		
	return ($.support.opacity)? false:true;
}



/*	変数セットの有無
==================================================*/

function isset( data ){
	return ( typeof( data ) != 'undefined' );
}



/*	タグ取得関数
==================================================*/

function get_tag( object ) {
		return object.get(0).tagName.toLowerCase();
}



/*	ファイル名取得関数
==================================================*/

function get_file_name( src ) {
	return src.substr(0,src.lastIndexOf( "." ));
}



/*	拡張子取得関数
==================================================*/

function get_file_type( src ) {
	return src.substr(src.lastIndexOf( "." ));
}
		
		
	
/*	マウスオーバー画像の取得
==================================================*/

function get_hover_src( src, suffix ) {
	
	suffix = ( suffix === undefined )? gl_general_options['image_hover_suffix']:suffix;
	
	file_name = get_file_name(src);
	file_type = get_file_type(src);
	
	return file_name+ suffix+ file_type;
}
	
	

/*	マウスアウト画像の取得
==================================================*/

function get_out_src( src, suffix ) {
	
	suffix = ( suffix === undefined )? gl_general_options['image_hover_suffix']:suffix;
	
	file_name = get_file_name(src);
	file_type = get_file_type(src);
	
	return file_name.replace( suffix, '' )+ file_type;
}


/*	機種判別
==================================================*/

function is_iOS() {
	if(	navigator.userAgent.indexOf('iPhone') > 0 ||
		navigator.userAgent.indexOf('iPod') > 0 &&
		navigator.userAgent.indexOf('iPad') == -1
		) {
			return true;
	} else {
			return false;
	}
}



/*	IEforEach対応
==================================================*/

// Production steps of ECMA-262, Edition 5, 15.4.4.18
// Reference: http://es5.github.com/#x15.4.4.18
if ( !Array.prototype.forEach ) {
  Array.prototype.forEach = function( callback, thisArg ) {

    var T, k;

    if ( this == null ) {
      throw new TypeError( " this is null or not defined" );
    }

    // 1. Let O be the result of calling ToObject passing the |this| value as the argument.
    var O = Object(this);

    // 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".
    // 3. Let len be ToUint32(lenValue).
    var len = O.length >>> 0; // Hack to convert O.length to a UInt32

    // 4. If IsCallable(callback) is false, throw a TypeError exception.
    // See: http://es5.github.com/#x9.11
    if ( {}.toString.call(callback) != "[object Function]" ) {
      throw new TypeError( callback + " is not a function" );
    }

    // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
    if ( thisArg ) {
      T = thisArg;
    }

    // 6. Let k be 0
    k = 0;

    // 7. Repeat, while k < len
    while( k < len ) {

      var kValue;

      // a. Let Pk be ToString(k).
      //   This is implicit for LHS operands of the in operator
      // b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.
      //   This step can be combined with c
      // c. If kPresent is true, then

      if ( k in O ) {

        // i. Let kValue be the result of calling the Get internal method of O with argument Pk.
        kValue = O[ k ];

        // ii. Call the Call internal method of callback with T as the this value and
        // argument list containing kValue, k, and O.
        callback.call( T, kValue, k, O );
      }
      // d. Increase k by 1.
      k++;
    }
    // 8. return undefined
  };
}