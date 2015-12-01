

var cachedScrollbarWidth, supportsOffsetFractions,
    max = Math.max,
    abs = Math.abs,
    round = Math.round,
    rhorizontal = /left|center|right/,
    rvertical = /top|center|bottom/,
    roffset = /[\+\-]\d+(\.[\d]+)?%?/,
    rposition = /^\w+/,
    rpercent = /%$/,
    _position = $.fn.position;

$.widget( "ui.dialog",$.ui.dialog, {

    _create: function() {
        this.originalCss = {
            display: this.element[0].style.display,
            width: this.element[0].style.width,
            minHeight: this.element[0].style.minHeight,
            maxHeight: this.element[0].style.maxHeight,
            height: this.element[0].style.height
        };
        this.originalPosition = {
            parent: this.element.parent(),
            index: this.element.parent().children().index( this.element )
        };
        this.originalTitle = this.element.attr("title");
        this.options.title = this.options.title || this.originalTitle;

        this._createWrapper();


        this.element
            .show()
            .removeAttr("title")
            .addClass("ui-dialog-content ui-widget-content modal_body")
            .appendTo( this.uiDialog );

        this._createTitlebar();
        this._createButtonPane();

/*        if ( this.options.draggable && $.fn.draggable ) {
            this._makeDraggable();
        }
        if ( this.options.resizable && $.fn.resizable ) {
            this._makeResizable();
        }
/**/
        this._isOpen = false;
    },

    open: function() {
        var that = this;
        if ( this._isOpen ) {
            if ( this._moveToTop() ) {
                this._focusTabbable();
            }
            return;
        }

        this._isOpen = true;
        this.opener = $( this.document[ 0 ].activeElement );

        this._size();
        this._position();
        this._createOverlay();
        this._moveToTop( null, true );

        // Ensure the overlay is moved to the top with the dialog, but only when
        // opening. The overlay shouldn't move after the dialog is open so that
        // modeless dialogs opened after the modal dialog stack properly.

//        console.log(this.overlay.css('z-index')+':'+this.uiDialog.css('z-index'));

//        console.log(g_opened_dialogs);
        for (var i in g_opened_dialogs)
        {
            if (i == this.options.key)

                continue;
            $('#'+g_opened_dialogs[i]['id']).parent().css('display','none');
        }

        if ( this.overlay ) {
            //this.overlay.css( "z-index", this.uiDialog.css( "z-index" ) - 1 );
            this.uiDialog.css( "z-index", parseInt(this.overlay.css( "z-index")) + 1);
        }

        this._show( this.uiDialog, this.options.show, function() {
            that._focusTabbable();
            that._trigger( "focus" );
        });

        // Track the dialog immediately upon openening in case a focus event
        // somehow occurs outside of the dialog before an element inside the
        // dialog is focused (#10152)
        this._makeFocusTarget();

        this._trigger( "open" );
    },
    close: function( event ) {
		var activeElement,
			that = this;

		if ( !this._isOpen || this._trigger( "beforeClose", event ) === false ) {
			return;
		}

		this._isOpen = false;
		g_cnt_dialog--;
//		debug(g_cnt_dialog);

		this._destroyOverlay();		

		if ( !this.opener.filter(":focusable").focus().length ) {

			// support: IE9
			// IE9 throws an "Unspecified error" accessing document.activeElement from an <iframe>
			try {
				activeElement = this.document[ 0 ].activeElement;

				// Support: IE9, IE10
				// If the <body> is blurred, IE will switch windows, see #4520
				if ( activeElement && activeElement.nodeName.toLowerCase() !== "body" ) {

					// Hiding a focused element doesn't trigger blur in WebKit
					// so in case we have nothing to focus on, explicitly blur the active element
					// https://bugs.webkit.org/show_bug.cgi?id=47182
					$( activeElement ).blur();
				}
			} catch ( error ) {}
		}
		this._destroy( );
/*
		this._hide( this.uiDialog, this.options.hide, function() {
			that._trigger( "close", event );
		});
/**/
		
		d_id = gDialogId();
//		$("div#"+gDialogId()).dialog("destroy");
		$("div#"+d_id).remove();
		for ( var i in g_opened_dialogs) {
			if(g_opened_dialogs[i]['id'] == d_id)
			{
                if (g_opened_dialogs[i]['preview_url'])
                    DynamicHistory.SetUrl(g_opened_dialogs[i]['preview_url']);
                else
                    DynamicHistory.SetUrl(website);

				delete(g_opened_dialogs[i]);
				break;
			}			
		}
//		debug("div#"+gDialogId());
        var last_dialog;
        for (var i in g_opened_dialogs)
        {
            last_dialog = g_opened_dialogs[i]['id'];
        }
//        console.log(last_dialog);
        $('#'+last_dialog).parent().css('display','');


	},
	_createOverlay: function() {
//		if(g_cnt_dialog > 1)			return;

		if ( !this.options.modal ) {
			return;
		}

		var that = this,
			widgetFullName = this.widgetFullName;
		if ( !$.ui.dialog.overlayInstances ) {
			// Prevent use of anchors and inputs.
			// We use a delay in case the overlay is created from an
			// event that we're going to be cancelling. (#2804)
			this._delay(function() {
				// Handle .dialog().dialog("close") (#4065)
				if ( $.ui.dialog.overlayInstances ) {
					this.document.bind( "focusin.dialog", function( event ) {
						if ( !that._allowInteraction( event ) ) {
							event.preventDefault();
							$(".ui-dialog:visible:last .ui-dialog-content")
								.data( widgetFullName )._focusTabbable();
						}
					});
				}
			});
		}

		this.overlay = $("<div>")
			.attr("id","dialog-overlay")
			.addClass("ui-widget-overlay"+($.ui.dialog.overlayInstances > 0 ? "2" : "")+" ui-front")
			.appendTo( this._appendTo() )
			.attr("onClick",'$(".ui-dialog-titlebar-close:last").click();')
			//.css("height",$( window ).height())
        ;
		if ($.ui.dialog.overlayInstances > 0)
			this.overlay.css ("zIndex",99+$.ui.dialog.overlayInstances);
		this._on( this.overlay, {
			mousedown: "_keepFocus"
		});
		$.ui.dialog.overlayInstances++;
	},	
	_destroyOverlay: function() {
//		if(g_cnt_dialog > 1)			return;
		
		if ( !this.options.modal ) {
			return;
		}
	
		if ( this.overlay ) {
			$.ui.dialog.overlayInstances--;
	
			if ( !$.ui.dialog.overlayInstances ) {
				this.document.unbind( "focusin.dialog" );
			}

			$("html").removeClass("no_scroll");

			this.overlay.remove();
			this.overlay = null;
		}
	},
    _title: function(title) {
        if (!this.options.title ) {
            title.html("&#160;");
        } else {
            title.html(this.options.title);
        }
    },

    _size: function() {
        // If the user has resized the dialog, the .ui-dialog and .ui-dialog-content
        // divs will both have width and height set, so we need to reset them
        var nonContentHeight, minContentHeight, maxContentHeight,
            options = this.options;

        // Reset content sizing
        this.element.show().css({
//            width: "auto",
            minHeight: 0,
            maxHeight: "none",
            height: 0
        });

        if ( options.minWidth > options.width ) {
            options.width = options.minWidth;
        }

        // reset wrapper sizing
        // determine the height of all the non-content elements
        nonContentHeight = 0;//
        this.uiDialog.css({
            ///    height: "auto",
            width: options.width
        });//.outerHeight();
        minContentHeight = Math.max( 0, options.minHeight - nonContentHeight );
        maxContentHeight = typeof options.maxHeight === "number" ?
            Math.max( 0, options.maxHeight - nonContentHeight ) :
            "none";

        if ( options.height === "by_resolution" && $(window).height() > maxContentHeight)
            options.height = "auto";

        if ( options.height === "auto" )
        {
            this.element.css({
                minHeight: minContentHeight,
                maxHeight: maxContentHeight,
                height: "auto"
            });
        }
        else if ( options.height === "by_resolution" )
        {
                this.element.css({
                    height: ($(window).height()-50-15)
                });
        }
        else {
            this.element.height( Math.max( 0, options.height - nonContentHeight ) );
        }

        if ( this.uiDialog.is( ":data(ui-resizable)" ) ) {
            this.uiDialog.resizable( "option", "minHeight", this._minHeight() );
        }
    },

/////////////////////////////////////////////////////////////////////////
    _makeFocusTarget: function() {
        this._untrackInstance();
        this._trackingInstances().unshift( this );
    },

    _untrackInstance: function() {
        var instances = this._trackingInstances(),
            exists = $.inArray( this, instances );
        if ( exists !== -1 ) {
            instances.splice( exists, 1 );
        }
    },

    _trackingInstances: function() {
        var instances = this.document.data( "ui-dialog-instances" );
        if ( !instances ) {
            instances = [];
            this.document.data( "ui-dialog-instances", instances );
        }
        return instances;
    },

});


$.ui.position = {
    fit: {
        left: function( position, data ) {
            var within = data.within,
                withinOffset = within.isWindow ? within.scrollLeft : within.offset.left,
                outerWidth = within.width,
                collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                overLeft = withinOffset - collisionPosLeft,
                overRight = collisionPosLeft + data.collisionWidth - outerWidth - withinOffset,
                newOverRight;

            // element is wider than within
            if ( data.collisionWidth > outerWidth ) {
                // element is initially over the left side of within
                if ( overLeft > 0 && overRight <= 0 ) {
                    newOverRight = position.left + overLeft + data.collisionWidth - outerWidth - withinOffset;
                    position.left += overLeft - newOverRight;
                    // element is initially over right side of within
                } else if ( overRight > 0 && overLeft <= 0 ) {
                    position.left = withinOffset;
                    // element is initially over both left and right sides of within
                } else {
                    if ( overLeft > overRight ) {
                        position.left = withinOffset + outerWidth - data.collisionWidth;
                    } else {
                        position.left = withinOffset;
                    }
                }
                // too far left -> align with left edge
            } else if ( overLeft > 0 ) {
                position.left += overLeft;
                // too far right -> align with right edge
            } else if ( overRight > 0 ) {
                position.left -= overRight;
                // adjust based on position and margin
            } else {
                position.left = max( position.left - collisionPosLeft, position.left );
            }
        },
        top: function( position, data ) {
            var within = data.within,
                withinOffset = within.isWindow ? within.scrollTop : within.offset.top,
                outerHeight = data.within.height,
                collisionPosTop = position.top - data.collisionPosition.marginTop,
                overTop = withinOffset - collisionPosTop,
                overBottom = collisionPosTop + data.collisionHeight - outerHeight - withinOffset,
                newOverBottom;

            // element is taller than within
            if ( data.collisionHeight > outerHeight ) {
                // element is initially over the top of within
                if ( overTop > 0 && overBottom <= 0 ) {
                    newOverBottom = position.top + overTop + data.collisionHeight - outerHeight - withinOffset;
                    position.top += overTop - newOverBottom;
                    // element is initially over bottom of within
                } else if ( overBottom > 0 && overTop <= 0 ) {
                    position.top = withinOffset;
                    // element is initially over both top and bottom of within
                } else {
                    if ( overTop > overBottom ) {
                        position.top = withinOffset + outerHeight - data.collisionHeight;
                    } else {
                        position.top = withinOffset;
                    }
                }
                // too far up -> align with top
            } else if ( overTop > 0 ) {
                position.top += overTop;
                // too far down -> align with bottom edge
            } else if ( overBottom > 0 ) {
                position.top -= overBottom;
                // adjust based on position and margin
            } else {
                position.top = max( position.top - collisionPosTop, position.top );
            }
        }
    },
    flip: {
        left: function( position, data ) {
            var within = data.within,
                withinOffset = within.offset.left + within.scrollLeft,
                outerWidth = within.width,
                offsetLeft = within.isWindow ? within.scrollLeft : within.offset.left,
                collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                overLeft = collisionPosLeft - offsetLeft,
                overRight = collisionPosLeft + data.collisionWidth - outerWidth - offsetLeft,
                myOffset = data.my[ 0 ] === "left" ?
                    -data.elemWidth :
                    data.my[ 0 ] === "right" ?
                        data.elemWidth :
                        0,
                atOffset = data.at[ 0 ] === "left" ?
                    data.targetWidth :
                    data.at[ 0 ] === "right" ?
                        -data.targetWidth :
                        0,
                offset = -2 * data.offset[ 0 ],
                newOverRight,
                newOverLeft;

            if ( overLeft < 0 ) {
                newOverRight = position.left + myOffset + atOffset + offset + data.collisionWidth - outerWidth - withinOffset;
                if ( newOverRight < 0 || newOverRight < abs( overLeft ) ) {
                    position.left += myOffset + atOffset + offset;
                }
            } else if ( overRight > 0 ) {
                newOverLeft = position.left - data.collisionPosition.marginLeft + myOffset + atOffset + offset - offsetLeft;
                if ( newOverLeft > 0 || abs( newOverLeft ) < overRight ) {
                    position.left += myOffset + atOffset + offset;
                }
            }
        },
        top: function( position, data ) {
            var within = data.within,
                withinOffset = within.offset.top + within.scrollTop,
                outerHeight = within.height,
                offsetTop = within.isWindow ? within.scrollTop : within.offset.top,
                collisionPosTop = position.top - data.collisionPosition.marginTop,
                overTop = collisionPosTop - offsetTop,
                overBottom = collisionPosTop + data.collisionHeight - outerHeight - offsetTop,
                top = data.my[ 1 ] === "top",
                myOffset = top ?
                    -data.elemHeight :
                    data.my[ 1 ] === "bottom" ?
                        data.elemHeight :
                        0,
                atOffset = data.at[ 1 ] === "top" ?
                    data.targetHeight :
                    data.at[ 1 ] === "bottom" ?
                        -data.targetHeight :
                        0,
                offset = -2 * data.offset[ 1 ],
                newOverTop,
                newOverBottom;
            if ( overTop < 0 ) {
                position.top = -9999;
                return;
                newOverBottom = position.top + myOffset + atOffset + offset + data.collisionHeight - outerHeight - withinOffset;
                if ( ( position.top + myOffset + atOffset + offset) > overTop && ( newOverBottom < 0 || newOverBottom < abs( overTop ) ) ) {
                    position.top += myOffset + atOffset + offset;
                }
            } else if ( overBottom > 0 ) {
                newOverTop = position.top - data.collisionPosition.marginTop + myOffset + atOffset + offset - offsetTop;
                if ( ( position.top + myOffset + atOffset + offset) > overBottom && ( newOverTop > 0 || abs( newOverTop ) < overBottom ) ) {
                    position.top += myOffset + atOffset + offset;
                }
            }
        }
    },
    flipfit: {
        left: function() {
            $.ui.position.flip.left.apply( this, arguments );
            $.ui.position.fit.left.apply( this, arguments );
        },
        top: function() {
            $.ui.position.flip.top.apply( this, arguments );
            $.ui.position.fit.top.apply( this, arguments );
        }
    }
};