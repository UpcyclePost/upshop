(function(window, document) {
    var site;

    function UP() {
        this.init();
    }

    UP.prototype = {
        init: function() {
            $('nav#mobile-slide-menu').mmenu({
                offCanvas: {
                    position: "right",
                    zposition: "front"
                }
            });

            $('nav#mobile-slide-menu').removeClass('hidden');

            this.menu = new Menu();
        }
    };

    function Menu() {
        this.init();
    }

    Menu.prototype = {


        init: function() {
            $('.mobile-menu').on('click', function(e) {
                var menu = $('#main-menu');

                if (menu.hasClass('show')) {
                    menu.removeClass('show');
                    $(document).unbind('click', site.menu.close);
                } else {
                    menu.addClass('show');

                    $(document).on('click', site.menu.close);
                }
                e.preventDefault();
            });
        },

        close: function() {
            if (!$(event.target).hasClass('mobile-menu') && $('.main-menu').has($(event.target)).length == 0) {
                $('#main-menu').removeClass('show');
                $(document).unbind('click', site.menu.close);

                return false;
            }
        }
    };

    site = new UP();
})(this, this.document);