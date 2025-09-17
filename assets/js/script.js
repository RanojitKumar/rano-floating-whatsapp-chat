/*
 * Rano Floating WhatsApp Chat - JavaScript
 * Enhanced functionality for the chat button
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Initialize chat button functionality
        initChatButton();
        
        // Add click tracking
        trackChatButtonClick();
        
        // Handle responsive behavior
        handleResponsiveBehavior();
    });
    
    /**
     * Initialize chat button
     */
    function initChatButton() {
        var $chatButton = $('#rfwc-chat-button');
        
        if ($chatButton.length === 0) {
            return;
        }
        
        // Add accessibility attributes
        $chatButton.find('a').attr({
            'role': 'button',
            'tabindex': '0'
        });
        
        // Handle keyboard navigation
        $chatButton.find('a').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this)[0].click();
            }
        });
        
        // Add hover effects for better UX
        $chatButton.on('mouseenter', function() {
            $(this).addClass('rfwc-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('rfwc-hover');
        });
        
        // Show button with fade-in effect after page load
        setTimeout(function() {
            $chatButton.addClass('rfwc-visible');
        }, 1000);
    }
    
    /**
     * Track chat button clicks for analytics
     */
    function trackChatButtonClick() {
        $('#rfwc-chat-button a').on('click', function(e) {
            // Track click event
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    event_category: 'WhatsApp Chat',
                    event_label: 'Floating Button'
                });
            }
            
            // Track with Google Analytics (legacy)
            if (typeof ga !== 'undefined') {
                ga('send', 'event', 'WhatsApp Chat', 'click', 'Floating Button');
            }
            
            // Custom event for developers
            $(document).trigger('rfwc_button_clicked', {
                button_type: 'floating',
                timestamp: new Date().toISOString()
            });
        });
        
        // Track shortcode links
        $('.rfwc-shortcode-link').on('click', function(e) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    event_category: 'WhatsApp Chat',
                    event_label: 'Shortcode Link'
                });
            }
            
            if (typeof ga !== 'undefined') {
                ga('send', 'event', 'WhatsApp Chat', 'click', 'Shortcode Link');
            }
            
            $(document).trigger('rfwc_button_clicked', {
                button_type: 'shortcode',
                timestamp: new Date().toISOString()
            });
        });
    }
    
    /**
     * Handle responsive behavior
     */
    function handleResponsiveBehavior() {
        var $chatButton = $('#rfwc-chat-button');
        
        if ($chatButton.length === 0) {
            return;
        }
        
        // Adjust button size on very small screens
        function adjustButtonSize() {
            var windowWidth = $(window).width();
            
            if (windowWidth < 480) {
                $chatButton.addClass('rfwc-small-screen');
            } else {
                $chatButton.removeClass('rfwc-small-screen');
            }
        }
        
        // Initial adjustment
        adjustButtonSize();
        
        // Adjust on window resize
        $(window).on('resize', debounce(adjustButtonSize, 250));
        
        // Handle scroll behavior (optional fade on scroll)
        var lastScrollTop = 0;
        $(window).on('scroll', debounce(function() {
            var currentScroll = $(this).scrollTop();
            
            if (currentScroll > lastScrollTop + 10) {
                // Scrolling down
                $chatButton.addClass('rfwc-scroll-down');
            } else if (currentScroll < lastScrollTop - 10) {
                // Scrolling up
                $chatButton.removeClass('rfwc-scroll-down');
            }
            
            lastScrollTop = currentScroll;
        }, 100));
    }
    
    /**
     * Debounce function to limit function calls
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    /**
     * Add custom CSS for enhanced behavior
     */
    function addCustomStyles() {
        var customCSS = `
            <style id="rfwc-custom-styles">
                #rfwc-chat-button {
                    opacity: 0;
                    transform: scale(0.8);
                    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                }
                
                #rfwc-chat-button.rfwc-visible {
                    opacity: 1;
                    transform: scale(1);
                }
                
                #rfwc-chat-button.rfwc-small-screen a {
                    width: 50px !important;
                    height: 50px !important;
                }
                
                #rfwc-chat-button.rfwc-scroll-down {
                    opacity: 0.7;
                    transform: scale(0.9);
                }
                
                #rfwc-chat-button.rfwc-hover a {
                    animation-play-state: paused;
                }
                
                @media (max-width: 480px) {
                    #rfwc-chat-button.rfwc-small-screen {
                        bottom: 10px !important;
                        right: 10px !important;
                    }
                    
                    #rfwc-chat-button.rfwc-small-screen.rfwc-position-bottom-left {
                        left: 10px !important;
                    }
                }
            </style>
        `;
        
        if ($('#rfwc-custom-styles').length === 0) {
            $('head').append(customCSS);
        }
    }
    
    // Add custom styles
    addCustomStyles();
    
})(jQuery);