/**
 * UpSolution Element: Interactive Text
 */
!function( $ ) {
	"use strict";
	/**
	 * @class WItext
	 * @param {string|node} container
	 */
	$us.WItext = function( container ) {
		// Variables
		var defaultOptions = {
			html_nbsp_char: true
		};

		// Elements
		this.$container = $( container );
		var $parts = this.$container.find( '.w-itext-part' );

		if ( $parts.length === 0 ) {
			return; // No animated parts
		}

		// Get options
		var options = $.extend( defaultOptions, this.$container[ 0 ].onclick() || {} );
		// Delete data everywhere except for the preview of the usbuilder,
		// the data may be needed again to restore the elements.
		if ( ! $us.usbPreview() ) this.$container.removeAttr( 'onclick' );

		// Set options
		var type = this.$container.usMod( 'type' );
		this.animateChars = ( type.substring( type.length - 'chars'.length ).toLowerCase() === 'chars' );
		this.duration = parseInt( options.duration ) || 1000;
		this.delay = parseInt( options.delay ) || 5000;
		this.dynamicColor = ( options.dynamicColor || '' );
		this.disablePartAnimation = options.disablePartAnimation || false;
		this.animateDurations = []; // Time of all animations
		this.type = this.animateChars ? type.substring( 0, type.length - 'chars'.length ) : type;
		this.nbsp_char = options.html_nbsp_char ? '&nbsp;' : ' ';

		// Creating objects for all parts
		this.parts = [];
		$parts
			.css( { transitionDuration: this.duration + 'ms' } )
			.each( function( index, part ) {
				var part = {
					$node: $( part ),
					currentState: 0,
					states: part.onclick() || []
				};
				// Delete data everywhere except for the preview of the usbilder,
				// the data may be needed again to restore the elements
				if ( ! $us.usbPreview() ) part.$node.removeAttr( 'onclick' );
				if ( this.dynamicColor ) {
					part.$node.css( 'color', this.dynamicColor );
				}
				this.parts[ index ] = part;
			}.bind( this ) );

		// Start first animation
		var timer = $ush.timeout( function() {
			this.parts.map( function( part ) {
				this._events.startAnimate.call( this, part );
			}.bind( this ) );
			$ush.clearTimeout( timer );
		}.bind( this ), this.delay );
	};
	// Export api
	$us.WItext.prototype = {
		_events: {
			/**
			 * Starts the next animation step.
			 *
			 * @param {object} part
			 */
			startAnimate: function( part ) {
				part.currentState = ( part.currentState === part.states.length - 1 ) ? 0 : ( part.currentState + 1 );
				this.render.call( this, part );
			},
			/**
			 * Animation restart.
			 *
			 * @param {object} part
			 */
			restartAnimate: function( part ) {
				$ush.timeout( this._events.startAnimate.bind( this, part ), this.delay );
			},
			/**
			 * Clear unwanted items or data after animation.
			 *
			 * @param {object} part
			 */
			clearAnimation: function( part ) {
				var text = $ush.toString( part.states[ part.currentState ] ).replace( ' ', this.nbsp_char );
				part.$node
					.html( text )
					.css( 'width', '' );
				if ( this.type === 'typing' && text.trim() && text !== this.nbsp_char ) {
					part.$node.append( '<i class="w-itext-cursor"></i>' );
				}
				if ( part.curDuration === Math.max.apply( null, this.animateDurations ) ) {
					this.animateDurations = [];
					this.parts.map( function( _part ) {
						this._events.restartAnimate.call( this, _part );
					}.bind( this ) );
				}
			}
		},
		/**
		 * Rendering animation elements.
		 *
		 * @param {object} part
		 */
		render: function( part ) {
			var nextValue = part.states[ part.currentState ],
				$curSpan = part.$node.wrapInner( '<span></span>' ).children( 'span' ),
				$nextSpan = $( '<span class="measure"></span>' ).html( nextValue.replace( ' ', this.nbsp_char ) ).appendTo( part.$node ),
				nextWidth = $nextSpan.width(),
				outType = 'fadeOut',
				startDelay = 0;
			part.curDuration = this.duration;
			// Remove typing chars
			if ( this.type === 'typing' ) {
				var oldValue = $curSpan.text().trim() + ' ',
					removeDuration = Math.floor( part.curDuration / 3 );
				startDelay = Math.max.apply( null, [ startDelay, ( removeDuration * oldValue.length ) ] );
				for ( var i = 0; i < oldValue.length; i ++ ) {
					$curSpan.text( oldValue );
					$ush.timeout( function() {
						var text = $curSpan.text();
						$curSpan.text( text.substring( 0, text.length - 1 ) );
					}.bind( this ), removeDuration * i );
				}
			}
			// Start animation
			$ush.timeout( function() {
				part.$node.addClass( 'notransition' );
				if ( ! this.disablePartAnimation ) {
					part.$node.css( 'width', part.$node.width() );
				}
				$ush.timeout( function() {
					part.$node.removeClass( 'notransition' );
					if ( ! this.disablePartAnimation ) {
						part.$node.css( 'width', nextWidth );
					}
				}.bind( this ), 25 );
				if ( this.type !== 'typing' ) {
					$curSpan
						.css({
							position: 'absolute',
							top: 0,
							left: 0,
							width: ! this.disablePartAnimation ? nextWidth : '',
							transitionDuration: ( this.duration / 5 ) + 'ms'
						})
						.addClass( 'animated_' + outType );
				}
				if ( ! this.disablePartAnimation ) {
					$nextSpan.css( 'width', nextWidth );
				}
				$nextSpan.removeClass( 'measure' ).prependTo( part.$node );
				if ( this.animateChars ) {
					$nextSpan.empty();
					if ( this.type === 'typing' ) {
						$nextSpan.append( '<span class="w-itext-part-nospan"></span>' );
					}
					for ( var i = 0; i < nextValue.length; i ++ ) {
						var $char = ( ( nextValue[ i ] !== ' ' ) ? nextValue[ i ] : this.nbsp_char );
						if ( this.type !== 'typing' ) {
							$char = $( '<span>' + $char + '</span>' );
							$char
								.css( 'transition-duration', part.curDuration + 'ms' )
								.appendTo( $nextSpan );
							$char.appendTo( $nextSpan );
						}
						$ush.timeout( function( $char ) {
							if ( this.type !== 'typing' ) {
								$char.addClass( 'animated_' + this.type );
							} else {
								var $text = $( '> span:first', $nextSpan );
								$text.html( $text.html() + $char );
							}
						}.bind( this, $char ), part.curDuration * i );
					}

					if ( this.type === 'typing' && $ush.toString( nextValue ).trim() && nextValue !== this.nbsp_char ) {
						$nextSpan.append( '<i class="w-itext-cursor"></i>' );
					}
					part.curDuration *= ( nextValue.length + 1 );
				} else {
					$nextSpan.wrapInner( '<span></span>' ).children( 'span' ).css( {
						'animation-duration': this.duration + 'ms'
					} ).addClass( 'animated_' + this.type );
				}
				this.animateDurations.push( part.curDuration );

				// Clear and next animation state
				$ush.timeout( this._events.clearAnimation.bind( this, part ), part.curDuration + Math.floor( this.delay / 3 ) );
			}.bind( this ), startDelay );
		}
	};

	$.fn.wItext = function( options ) {
		return this.each( function() {
			$( this ).data( 'wItext', new $us.WItext( this, options ) );
		} );
	};

	$( function() {
		$( '.w-itext' ).wItext();
	} );

}( jQuery );
