
var AsikartEasySet = 
{	
	// Constructor
	init: function (opt, config) {
		this.config = {
			colorTable: {} ,
			opacityEffect: {} ,
			foldContent: {}
		}
		
		Object.merge( this.config , config ) ;
		
		Object.each( opt , function( v , k ) {
			if( true == v ) eval( 'AsikartEasySet.'+k+'();' );
		} );
	}
	
	,
	
	// Color Table
	colorTable: function () {
		window.addEvent( 'domready' , function(){
			
			var ct = $$( '.colorTable' );
			
			ct.each( function( v ){
				if( v.getElements('tbody') ) {
					var tr 	= v.getElements('tbody tr');
					var k	= 0 ;
				}else{
					var tr 	= v.getElements('tr');
					var k	= 1 ;
				}

				tr.each( function( t ) {
					t.addClass( 'row'+k );
					k = 1 - k ;
				} );
			} );
			
		} );
	}
	
	,
	
	// opacity effect
	opacityEffect: function ()
	{
		window.addEvent('domready', function() {
	    	
			var d = AsikartEasySet.config.opacityEffect.duration || 300 ;
	    	var n = AsikartEasySet.config.opacityEffect.opacity || '0.8' ;
			var e = $$('.hasOpacity').setStyle('opacity',n);
			e.set('tween', {duration: d });
			
			e.addEvent('mouseenter',function(){
				this.fade( 1 );
			});
			
			e.addEvent('mouseleave',function(){
				this.fade( n );
			});	
			
		});
	}
	
	,
	
	//Fold Content
	foldContent: function ()
	{
		window.addEvent( 'domready' , function(){
			
			$$( 'div.ak-fold-warp' ).each( function(fold_warp){
				var w = fold_warp.getElement( '.ak-fold-outter' ) ;
				var b = fold_warp.getElement('.ak-fold-button');
				var foldSlide = new Fx.Slide( w );
				var status = 0 ;
				
				var openImg = AsikartEasySet.config.root+'/plugins/system/asikart_easyset/imgs/icons/folder-open.png' ;
				var closeImg = AsikartEasySet.config.root+'/plugins/system/asikart_easyset/imgs/icons/folder-close.png' ;
				
				foldSlide.hide();
				b.setStyle( 'background-image' , 'url('+openImg+')' );
				
				b.addEvent( 'click' , function(b){
					b.stop();
					foldSlide.toggle();
					status = 1 - status ;
				});
				
				foldSlide.addEvent( 'complete' , function(){
					if( status == 1 )
						b.setStyle( 'background-image' , 'url('+closeImg+')' );
					else
						b.setStyle( 'background-image' , 'url('+openImg+')' );
				} );
			});
			
		} ); // end event domready
	}
	
	,
	
	// FixingElement
	fixingElement: function()
	{
		window.addEvent( 'domready' , function(){
			var f = $$('.hasFixed') ;
			
			
			f.each(function(f){
				var w = f.getStyle( 'width' );
				f.setStyle( 'width' , w ) ;
				var coords = f.getCoordinates(); 
				
				window.addEvent('scroll', function(){ 
			             
		             var pageSize = window.getSize(); 
		             var pageScroll = window.getScroll();
		             
		             if (coords.top < pageScroll.y) { 
		                 f.setStyle('top', 0); 
		                 f.setStyle('position', 'fixed'); 
		             } 
		             if (coords.top > pageScroll.y) { 
		                 //f.setStyle('top', pageScroll.y+pageSize.y-coords.height); 
		                 f.setStyle('position', 'static'); 
		             } 
		         }); 
			} );
		} );
	}
	
	,
	
	// Smooth Scroll
	smoothScroll: function()
	{
		window.addEvent( 'domready' , function(){
			new Fx.SmoothScroll({ duration: 300},window);
		} );
	}
}

