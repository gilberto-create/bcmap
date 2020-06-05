////
//
//	Generic Smooth Scroll with jQuery.
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



/*	Generic Smooth Scroll
==================================================*/

$.fn.GenericSmoothScroll = function( user_option ){
	
	
	
	/*	デフォルトオプション
	==================================================*/
	
	this.plugin_name = 'Generic Smooth Scroll';
	this.original_option = {
			'pagetop_id':'top'
		}
	this.option_list = new Array(
			'animation_easing',	//	動作
			'scroll_duration',	//	動作時間
			'debug'				//	デバッグ機能
		)
	
	
	
	/*	デバッグ用メソッド
	==================================================*/
	
	this.flg = function(s){if( this.option['debug'] === true ) console.log( this.plugin_name+ ' : ▼ start : '+ s );}
	this.msg = function(s){if( this.option['debug'] === true ) console.log( this.plugin_name+ " : \n\t"+ s );}
	
	
	
	/*	オプションの最適化
	==================================================*/
	
	this.optimizer = function(){
	this.flg('optimizer');
	}
	
	
	
	/*	オプションの初期化
	==================================================*/
	
	this.set_option = function(){
		
		if( !isset(this.option) ) {
			
			this.option = {};
			for( var name in this.option_list ) {
				this.option[this.option_list[name]] = gl_general_options[this.option_list[name]];
			}
			for( var name in this.original_option ) {
				this.option[name] = this.original_option[name];
			}
		}
		
		if( isset(user_option) ) {
			
			for( var name in user_option ) {
				this.option[name] = user_option[name];
			}
		}
		
		this.flg('set_option');
		this.optimizer();
	}
	
	
	
	/*	コンストラクタ
	==================================================*/	
	
	
	//	イベントリスナー内参照用オブジェクト
	var self = this;
	this.set_option();
	
	
	//	スクロール実装
	this.click(function(){
	self.flg('click');	
		
			href = $(this).attr("href");
			
			
			if( $(href).attr("id") ) {
			self.msg('scroll target');
				scroll_opsition = $(href).offset().top;
				
			} else if( href == '#'+ self.option['pagetop_id'] ) {
			self.msg('scroll top');
				scroll_opsition = 0;
				
			} else {
			self.msg('scroll cansel');
				return false;	
				
			}
			
			
			$('html,body').animate(
					{
						scrollTop: scroll_opsition
					},
					self.option['scroll_duration'],
					self.option['animation_easing']
				);
				
			return false;			
		});
}