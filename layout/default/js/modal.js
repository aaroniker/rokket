var modal = (function ( w, d, h ) {
    'use strict';
	
    var _cache = {
        create:     document.createElement,
        settings:   [], 
        overlay:    [],
        wrapper:    [],
        container:  [],
        modal:      [],
        content:    [],
        inline:     [],
        size:       [],
        close:      [],
        open:       [],
        scroll:     [],
        item:       -1
    },
    _defaults = {
        target:         null,
        cache:          false,
        escKey:         true,
        zIndex:         'auto',
        overlay:        true,
        overlayColor:   '#000',
        overlayOpacity: 0.8,
        overlayClose:   true,
        overlaySpeed:   300,
        overlayEffect:  'auto',
        width:          null,
        effect:         'fadein',           // fadein | slide | newspaper | fall | sidefall | blur | flip | sign | superscaled | slit | rotate | letmein | makeway | slip | corner | slidetogether | scale | door | push | contentscale.
        position:       'center, center',
        animation:      null,
        speed:          500,
        open:           null,
        complete:       null,
        close:          null
    },
    _config = {
        oldBrowser:         navigator.appVersion.indexOf('MSIE 8.') > -1 || navigator.appVersion.indexOf('MSIE 9.') > -1 || /(iPhone|iPad|iPod)\sOS\s6/.test(navigator.userAgent),
        overlay: {
            perspective:    ['letmein', 'makeway', 'slip'],
            together:       ['corner', 'slidetogether', 'scale', 'door', 'push', 'contentscale', 'simplegenie', 'slit', 'slip']
        },
        modal: {
            position:       ['slide', 'flip', 'rotate'] 
        }
    },
    _private = {
        init: function () {
            this
                .merge()
                .built()
                .check()
                .binds();
        },
        merge: function () {
            _cache.item++;

            _cache.settings.push(_utilities.extend( {}, _defaults, _cache.options ));
            delete _cache.options;

            if ( _cache.settings[_cache.item].overlayEffect === 'auto' ) {
                _cache.settings[_cache.item].overlayEffect = _cache.settings[_cache.item].effect;
            }

            return this;
        },
        built: function () {
            var zIndex = _utilities.zIndex();

            if ( zIndex === 2147483647 ) {
                zIndex = w.getComputedStyle(_cache.modal[_cache.item]).getPropertyValue('z-index');
            }

            h.classList.add('modal-open', 'modal-open-' + _cache.settings[_cache.item].overlayEffect);

            if ( _config.overlay.perspective.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) {
                _cache.scroll.push(h && h.scrollTop || d.body && d.body.scrollTop || 0);
                h.classList.add('modal-perspective');
                w.scrollTo(0, 0);
            }

            if ( !_cache.item ) {
                _cache.main = _cache.create.call(d, 'div');
                while ( d.body.firstChild ) {
                    _cache.main.appendChild(d.body.firstChild);
                }
                d.body.appendChild(_cache.main);
            }

            if ( _cache.settings[_cache.item].overlayEffect === 'push' ) {
                _cache.main.style.transitionDuration = _cache.settings[_cache.item].speed + 'ms';
            }

            _cache.main.classList.add(
                'modal-container',
                'modal-container-' + _cache.settings[_cache.item].overlayEffect
            );

            if ( _cache.settings[_cache.item].overlay ) {
                _cache.overlay.push(_cache.create.call(d, 'div'));
                _cache.overlay[_cache.item].classList.add(
                    'modal-overlay',
                    'modal-overlay-' + _cache.settings[_cache.item].overlayEffect
                );
                _cache.overlay[_cache.item].style.zIndex = zIndex + 2;
                _cache.overlay[_cache.item].style.backgroundColor = _cache.settings[_cache.item].overlayColor;

                if ( _config.overlay.perspective.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 || _config.overlay.together.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) {
                    _cache.overlay[_cache.item].style.opacity = _cache.settings[_cache.item].overlayOpacity;
                } else {
                    _cache.overlay[_cache.item].classList.add('modal-overlay-default');
                }

                if ( _config.overlay.together.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) {
                    _cache.overlay[_cache.item].style.transitionDuration = _cache.settings[_cache.item].speed + 'ms';
                } else {
                    _cache.overlay[_cache.item].style.transitionDuration = _cache.settings[_cache.item].overlaySpeed + 'ms';
                }

                d.body.insertBefore(_cache.overlay[_cache.item], d.body.lastChild.nextSibling);
            } else {
                _cache.overlay.push(null);
            }

            _cache.wrapper.push(_cache.create.call(d, 'div'));
            _cache.wrapper[_cache.item].classList.add(
                'modal-modal-wrapper',
                'modal-modal-wrapper-' + _cache.settings[_cache.item].effect
            );
            _cache.wrapper[_cache.item].style.zIndex = zIndex + 3;
            d.body.insertBefore(_cache.wrapper[_cache.item], d.body.lastChild.nextSibling);

            _cache.container.push(_cache.create.call(d, 'div'));
            _cache.container[_cache.item].classList.add(
                'modal-modal-container',
                'modal-modal-container-' + _cache.settings[_cache.item].effect
            );
            _cache.container[_cache.item].style.zIndex = zIndex + 4;

            if ( _config.modal.position.indexOf( _cache.settings[_cache.item].effect ) > -1 ) {
                if ( _cache.settings[_cache.item].animation !== null ) {
                    if ( _cache.settings[_cache.item].animation.indexOf(',') > -1 ) {
                        _cache.settings[_cache.item].animation = _cache.settings[_cache.item].animation.split(',');
                    } else {
                        _cache.settings[_cache.item].animation = [_cache.settings[_cache.item].animation];
                    }
                } else {
                    if ( _cache.settings[_cache.item].effect === 'slide' ) {
                        _cache.settings[_cache.item].animation = ['top'];
                    } else if ( _cache.settings[_cache.item].effect === 'flip' ) {
                        _cache.settings[_cache.item].animation = ['horizontal'];
                    } else {
                        _cache.settings[_cache.item].animation = ['bottom'];
                    }
                }
            }

            _cache.modal.push(_cache.create.call(d, 'div'));
            _cache.modal[_cache.item].classList.add(
                'modal-modal',
                'modal-modal-' + _cache.settings[_cache.item].effect + ( _config.modal.position.indexOf( _cache.settings[_cache.item].effect ) > -1 ? '-' + _cache.settings[_cache.item].animation[0].trim() : '' )
            );
            _cache.modal[_cache.item].style.transitionDuration = _cache.settings[_cache.item].speed + 'ms';
            _cache.modal[_cache.item].style.zIndex = zIndex + 4;
            _cache.wrapper[_cache.item].appendChild(_cache.container[_cache.item]).appendChild(_cache.modal[_cache.item]);

            return this;
        },
        check: function () {
            if ( typeof _cache.settings[_cache.item].open === 'function' ) {
                _cache.settings[_cache.item].open.call();
            }

            var topen = d.createEvent('Event');
            topen.initEvent('modal.open', true, true);
            d.dispatchEvent(topen);
			
            if ( _cache.settings[_cache.item].position.indexOf(',') > -1 ) {
                _cache.settings[_cache.item].position = _cache.settings[_cache.item].position.split(',');
                if ( _cache.settings[_cache.item].target.charAt(0) === '#' || ( _cache.settings[_cache.item].target.charAt(0) === '.' && _cache.settings[_cache.item].target.charAt(1) !== '/' ) ) {
                    if ( d.querySelector(_cache.settings[_cache.item].target) ) {
                        _cache.inline.push(_cache.create.call(d, 'div'));
                        _cache.content.push(d.querySelector(_cache.settings[_cache.item].target));
                        _cache.content[_cache.item].style.display = 'block';
                        _cache.content[_cache.item].parentNode.insertBefore(_cache.inline[_cache.item], _cache.content[_cache.item]);
                        this.size().open();
                    } else {
                        this.error();
                    }
                } else {
                    this.ajax();
                }
            } else {
                this.error();
            }
            return this;
        },
        ajax: function () {
            var _this = this,
                xhr = new XMLHttpRequest(),
                modal = _cache.create.call(d, 'div');
            xhr.onreadystatechange = function () {
                if( xhr.readyState === 4 ) {
                    if( xhr.status === 200 ) {
                        modal.innerHTML = xhr.responseText;
                        _cache.content.push(modal);
                        _cache.content[_cache.item].style.display = 'block';
                        if ( !/(iPhone|iPad|iPod)\sOS\s6/.test(navigator.userAgent) && _config.oldBrowser ) {
                            _cache.content[_cache.item].style.styleFloat = 'left';
                        } else {
                            _cache.content[_cache.item].style.cssFloat = 'left';
                        }
                        _cache.container[_cache.item].appendChild(_cache.content[_cache.item]);
                        _this.size().open();
                    } else {
                        _this.error();
                    }
                }
            };
            xhr.open('GET', _cache.settings[_cache.item].target + ( !_cache.settings[_cache.item].cache ? '?_=' + Date.now() : '' ), true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(null);
        },
        size: function () {
            var customw = _cache.content[_cache.item].offsetWidth;

            if ( !_cache.inline[_cache.item] ) {
                if ( !/(iPhone|iPad|iPod)\sOS\s6/.test(navigator.userAgent) && _config.oldBrowser ) {
                    _cache.content[_cache.item].style.styleFloat = 'none';
                } else {
                    _cache.content[_cache.item].style.cssFloat = "none";
                }
            }

            if ( !isNaN( _cache.settings[_cache.item].width ) && _cache.settings[_cache.item].width !== null ) {
                customw = parseInt( _cache.settings[_cache.item].width, 0);
            }

            _cache.size.push(customw);

            if ( _cache.size[_cache.item] + 60 >= w.innerWidth ) {
                _cache.container[_cache.item].style.width = 'auto';
                _cache.container[_cache.item].style.margin = '5%';
                _cache.wrapper[_cache.item].style.width = w.innerWidth + 'px';
                for ( var i = 0, elements = _cache.content[_cache.item].querySelectorAll(':scope > *'), t = elements.length; i < t; i++ ) {
                    if ( elements[i].offsetWidth > w.innerWidth ) {
                        elements[i].style.width = 'auto';
                    }
                }
            } else {
                switch ( _cache.settings[_cache.item].position[0].trim() ) {
                    case 'left':
                        _cache.container[_cache.item].style.marginLeft = 0;
                        break;
                    case 'right':
                        _cache.container[_cache.item].style.marginRight = 0;
                        break;
                }
                _cache.container[_cache.item].style.width = _cache.size[_cache.item] + 'px';
            }

            _cache.content[_cache.item].style.width = 'auto';
            _cache.modal[_cache.item].appendChild(_cache.content[_cache.item]);

            if ( _cache.content[_cache.item].offsetHeight >= w.innerHeight ) {
                _cache.container[_cache.item].style.marginTop = '5%';
                _cache.container[_cache.item].style.marginBottom = '5%';
            } else {
                var result;
                switch ( _cache.settings[_cache.item].position[1].trim() ) {
                    case 'top':
                        result = 0;
                        break;
                    case 'bottom':
                        result = w.innerHeight - _cache.content[_cache.item].offsetHeight + 'px';
                        break;
                    default:
                        result = w.innerHeight / 2 - _cache.content[_cache.item].offsetHeight / 2 + 'px';
                        break;
                 }
                _cache.container[_cache.item].style.marginTop = result;
            }

            return this;
        },
        open: function () {
            if ( _cache.settings[_cache.item].overlay ) {
                if ( _config.overlay.perspective.indexOf(_cache.settings[_cache.item].overlayEffect) > -1 || _config.overlay.together.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) {
                    _cache.overlay[_cache.item].classList.add('modal-overlay-open');
                } else {
                    _cache.overlay[_cache.item].style.opacity = _cache.settings[_cache.item].overlayOpacity;
                }

                _cache.main.classList.add('modal-container-open');

                if ( _config.overlay.together.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) {
                    _cache.wrapper[_cache.item].classList.add('modal-modal-open');
                } else {
                    var open = function () {
                        _cache.overlay[_cache.item].removeEventListener('transitionend', open);
                        _cache.wrapper[_cache.item].classList.add('modal-modal-open');
                    };
                    _cache.overlay[_cache.item].addEventListener('transitionend', open, false);
                }
            } else {
                _cache.wrapper[_cache.item].classList.add('modal-modal-open');
                _cache.main.classList.add('modal-container-open');
            }
            return this;
        },
        binds: function () {
            var _this = this;

            if ( _cache.settings[_cache.item].escKey ) {
                d.onkeydown = function ( event ) {
                    event = event || w.event;
                    if ( event.keyCode === 27 ) {
                        _this.close();
                    }
                };
            }

            if ( _cache.settings[_cache.item].overlayClose ) {
                _cache.wrapper[_cache.item].addEventListener('click', function ( event ) {
                    if ( event.target === _cache.wrapper[_cache.item] ) {
                        _this.close();
                    }
                }, false);
            }

            w.addEventListener('onorientationchange' in w ? 'orientationchange' : 'resize', function () {
                _this.responsive();
            }, false);

            var callback = function () {
                if ( !_cache.inline[_cache.item] ) {
                    for ( var i = 0, script = _cache.modal[_cache.item].getElementsByTagName('script'), t = script.length; i < t; i++ ) {
                        new Function( script[i].text )();
                    }
                }

                if ( _cache.settings[_cache.item] && typeof _cache.settings[_cache.item].complete === 'function' ) {
                    _cache.settings[_cache.item].complete.call();
                }

                var tcomplete = d.createEvent('Event');
                tcomplete.initEvent('modal.complete', true, true);
                d.dispatchEvent(tcomplete);
            };

            var complete = function () {
                callback();
                _cache.modal[_cache.item].removeEventListener('transitionend', complete);
            };
            if ( _config.oldBrowser ) {
                callback();
            } else {
                if ( _cache.settings[_cache.item].effect !== 'slit' ) {
                    _cache.modal[_cache.item].addEventListener('transitionend', complete, false);
                } else {
                    _cache.modal[_cache.item].addEventListener('animationend', complete, false);
                }
            }
        },
        close: function () {
            var start = function () {
                h.classList.remove('modal-open-' + _cache.settings[_cache.item].overlayEffect);

                if ( _cache.settings[_cache.item].overlay ) {
                    _cache.overlay[_cache.item].classList.add('modal-overlay-close');

                    if ( _cache.overlay[_cache.item].style.opacity ) {
                        _cache.overlay[_cache.item].style.opacity = 0;
                    }

                    _cache.overlay[_cache.item].classList.remove('modal-overlay-open');
                    _cache.main.classList.remove('modal-container-open');
                }
                if ( _config.oldBrowser || !_cache.overlay[_cache.item] ) {
                    end();
                } else {
                    var overlay = function () {
                        _cache.overlay[_cache.item].removeEventListener('transitionend', overlay);
                        end();
                    };
                    _cache.overlay[_cache.item].addEventListener('transitionend', overlay, false);
                }
            },
            end = function () {
                if ( !_cache.item ) {
                    h.classList.remove('modal-perspective', 'modal-open');
                    if ( typeof _cache.scroll[_cache.item] !== 'undefined' ) {
                        w.scrollTo(0, _cache.scroll[_cache.item]);
                    }
                }

                h.classList.remove('modal-open-' + _cache.settings[_cache.item].overlayEffect);

                if ( _cache.inline[_cache.item] ) {
                    if ( !/(iPhone|iPad|iPod)\sOS\s6/.test(navigator.userAgent) && _config.oldBrowser ) {
                        _cache.content[_cache.item].removeAttribute('width');
                        _cache.content[_cache.item].removeAttribute('display');
                    } else {
                        _cache.content[_cache.item].style.removeProperty('width');
                        _cache.content[_cache.item].style.removeProperty('display');
                    }

                    _cache.inline[_cache.item].parentNode.replaceChild(_cache.content[_cache.item], _cache.inline[_cache.item]);
                }

                _cache.main.classList.remove(
                    'modal-container-' + _cache.settings[_cache.item].overlayEffect
                );

                _cache.wrapper[_cache.item].parentNode.removeChild(_cache.wrapper[_cache.item]);

                if ( _cache.settings[_cache.item].overlay ) {
                    _cache.overlay[_cache.item].parentNode.removeChild(_cache.overlay[_cache.item]);
                }

                if ( typeof _cache.settings[_cache.item].close === 'function' ) {
                    _cache.settings[_cache.item].close.call();
                }

                var tclose = d.createEvent('Event');
                tclose.initEvent('modal.close', true, true);
                d.dispatchEvent(tclose);

                if ( !_cache.item ) {
                    for ( var contents = d.querySelectorAll('.modal-container > *'), i = 0, t = contents.length; i < t; i++ ) {
                        document.body.insertBefore(contents[i], _cache.main);
                    }
                    if ( _cache.main.parentNode ) {
                        _cache.main.parentNode.removeChild(_cache.main);
                    }
                }

                _cache.wrapper.pop();
                if ( _cache.inline[_cache.item] ) {
                    _cache.inline.pop();
                }
                _cache.content.pop();
                _cache.container.pop();
                _cache.modal.pop();
                _cache.overlay.pop();
                _cache.size.pop();
                _cache.settings.pop();
                _cache.scroll.pop();
                _cache.item--;
            };

            if ( _config.modal.position.indexOf( _cache.settings[_cache.item].effect ) > -1 && _cache.settings[_cache.item].animation.length > 1 ) {
                _cache.modal[_cache.item].classList.remove('modal-modal-' + _cache.settings[_cache.item].effect + '-' + _cache.settings[_cache.item].animation[0]);
                _cache.modal[_cache.item].classList.add('modal-modal-' + _cache.settings[_cache.item].effect + '-' + _cache.settings[_cache.item].animation[1].trim());
            }

            _cache.wrapper[_cache.item].classList.remove('modal-modal-open');

            if ( ( _config.oldBrowser || _config.overlay.together.indexOf( _cache.settings[_cache.item].overlayEffect ) > -1 ) ) {
                start();
            } else {
                var wrapper = function () {
                    _cache.wrapper[_cache.item].removeEventListener('transitionend', wrapper);
                    start();
                };
                _cache.wrapper[_cache.item].addEventListener('transitionend', wrapper, false);
            }
        },
        responsive: function () {
            for ( var i = 0, t = _cache.container.length, result; i < t; i++ ) {
                if ( _cache.size[i] + 60 >= w.innerWidth ) {
                    _cache.container[i].style.width = 'auto';
                    _cache.container[i].style.marginLeft = '5%';
                    _cache.container[i].style.marginRight = '5%';
                    _cache.wrapper[_cache.item].style.width = w.innerWidth + 'px';
                } else {
                    switch ( _cache.settings[_cache.item].position[0].trim() ) {
                        case 'left':
                            _cache.container[i].style.marginLeft = 0;
                            break;
                        case 'right':
                            _cache.container[i].style.marginRight = 0;
                            break;
                        default:
                            _cache.container[i].style.marginLeft = 'auto';
                            _cache.container[i].style.marginRight = 'auto';
                            break;
                    }
                    _cache.container[i].style.width = _cache.size[i] + 'px';
                    _cache.wrapper[_cache.item].style.width = 'auto';
                }

                if ( _cache.content[i].offsetHeight >= w.innerHeight ) {
                    _cache.container[i].style.marginTop = '5%';
                    _cache.container[i].style.marginBottom = '5%';
                } else {
                    switch ( _cache.settings[_cache.item].position[1] ) {
                        case 'top':
                            result = 0;
                            break;
                        case 'bottom':
                            result = w.innerHeight - _cache.content[i].offsetHeight + 'px';
                            break;
                        default:
                            result = w.innerHeight / 2 - _cache.content[i].offsetHeight / 2 + 'px';
                            break;
                    }
                    _cache.container[i].style.marginTop = result;
                }
            }
        },
        error: function () {
            alert('Error to load this target: ' + _cache.settings[_cache.item].target);
        }
    },
    _utilities = {
        zIndex: function () {
            if ( !w.getComputedStyle ) {
                w.getComputedStyle = function( el ) {
                    this.el = el;
                    this.getPropertyValue = function( prop ) {
                        var re = /(\-([a-z]){1})/g;
                        if ( prop == 'float' ) prop = 'styleFloat';
                        if ( re.test(prop) ) {
                            prop = prop.replace(re, function () {
                                return arguments[2].toUpperCase();
                            });
                        }
                        return el.currentStyle[prop] ? el.currentStyle[prop] : null;
                    };
                    return this;
                };
            }
            var zIndex = 0;
            if ( isNaN ( _cache.settings[_cache.item].zIndex ) ) {
                for ( var x = 0, elements = document.getElementsByTagName('*'), xLen = elements.length; x < xLen; x += 1 ) {
                    var val = w.getComputedStyle(elements[x]).getPropertyValue('z-index');
                    if ( val ) {
                        val =+ val;
                        if ( val > zIndex ) {
                            zIndex = val;
                        }
                    }
                }
            } else {
                zIndex = _cache.settings[_cache.item].zIndex;
            }
            return zIndex;
        },
        extend: function () {
            for ( var i = 1, arg = arguments.length; i < arg; i++ ) {
                for ( var key in arguments[i] ) {
                    if( arguments[i].hasOwnProperty(key) ) {
                        arguments[0][key] = arguments[i][key];
                    }
                }
            }
            return arguments[0];
        }
    };
	
    return {
        open: function ( options ) {
            _cache.options = options;
            _private.init();
        },
        close: function () {
            _private.close();
        }
    };
})( window, document, document.documentElement );