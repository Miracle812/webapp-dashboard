<?php
return array(
    'collections' => array(
        'default_layout_default_ie' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('ie/html5shiv.js');
                $collection->javascript('ie/respond.min.js');
                $collection->javascript('ie/excanvas.js');
            })->apply('JsMin');
        },

        'default_layout_reports' => function($collection)
            {
                $directory = $collection->directory('assets/css', function($collection)
                {
                    $collection->stylesheet('bootstrap.css');

                });
                $directory->apply('CssMin');
                $directory->apply('UriRewriteFilter');
                $directory = $collection->directory('assets/js', function($collection)
                    {
                        $collection->javascript('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
                        $collection->javascript('bootstrap.js');
                    });

                $directory->apply('JsMin');
            },

        'default_layout_default' => function($collection)
        {
            $directory = $collection->directory('assets/css', function($collection)
            {
                //$collection->javascript('https://maps.google.com/maps/api/js?sensor=true');
                $collection->stylesheet('bootstrap.css');
                $collection->stylesheet('animate.css');
                $collection->stylesheet('font-awesome.min.css');
                $collection->stylesheet('icon.css');
                $collection->stylesheet('font.css');
                $collection->stylesheet('app.css');
                $collection->stylesheet('navitas.css');
				$collection->stylesheet('custom.css');
                $collection->stylesheet('assets/packages/rating-stars/css/star-rating.min.css');
                $collection->stylesheet('/assets/js/jqueryui/jquery-ui.css');
                $collection->stylesheet('/packages/barryvdh/laravel-elfinder/css/elfinder.min.css');
                $collection->stylesheet('/packages/barryvdh/laravel-elfinder/css/theme.css');
            });
            $directory->apply('UriRewriteFilter');
            $directory->apply('CssMin');
            $directory = $collection->directory('assets/js', function($collection)
            {

                $collection->javascript('jquery/jquery.min.js')->raw();
                $collection->javascript('jqueryui/jquery-ui.min.js');
                $collection->javascript('/packages/barryvdh/laravel-elfinder/js/elfinder.min.js');
                $collection->javascript('assets/init/navichat.js');
                $collection->javascript('bootstrap.js');
                $collection->javascript('app.js');
                $collection->javascript('assets/packages/notyfications/jquery.noty.js');
                $collection->javascript('assets/packages/notyfications/layouts/bottomRight.js');
                $collection->javascript('assets/packages/notyfications/themes/default.js');
                $collection->javascript('slimscroll/jquery.slimscroll.min.js');
                $collection->javascript('assets/init/panel.default.init.js');
                $collection->javascript('assets/packages/rating-stars/js/star-rating.min.js');
                $collection->javascript('assets/packages/tinymce/tinymce.min.js')->raw();
                if(App::environment('_app')){
                    //$collection->javascript('assets/init/google-analitics.js');
                }
            });
            $directory->apply('UriRewriteFilter');
            $directory->apply('JsMin');
        },

        'error_layout' => function($collection)
        {
            $collection->directory('/assets/css', function($collection)
            {
                $collection->stylesheet('bootstrap_1.css');
                $collection->stylesheet('boostbox.css');
                $collection->stylesheet('boostbox_responsive.css');
                $collection->stylesheet('font-awesome.min.css');
                $collection->stylesheet('jquery-ui-boostbox.css');
                $collection->stylesheet('fullcalendar.css');
            })->apply('CssMin');
        },

        'admin_knowledge_edit' => function($collection)
        {
            $collection->directory('assets', function($collection)
            {
                $collection->javascript('init/admin.knowledge.edit.js');
            })->apply('JsMin');
        },
        'package_maskedinput' => function($collection)
        {
            $collection->directory('assets', function($collection)
            {
                $collection->javascript('init/jquery.maskedinput.min.js');
            })->apply('JsMin');
        },
        'package_datatables' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('datatable/css/datatables.css');
            })->apply('CssMin');

            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('assets/packages/datatable/js/jquery.dataTables.min.js');
                $collection->javascript('assets/packages/datatable/js/fnReloadAjax.js');
                $collection->javascript('assets/init/datatables.js');
            })->apply('JsMin');
        },
        'package_easypiechart' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('charts/easypiechart/jquery.easy-pie-chart.js');
                $collection->javascript('assets/init/easypiechart.js');
            })->apply('JsMin');
        },

        'package_touchspin' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->stylesheet('assets/js/spinner/jquery.bootstrap-touchspin.css');
            })->apply('CssMin');

            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('spinner/jquery.bootstrap-touchspin.min.js');
            })->apply('JsMin');
        },

        'package_datetimepicker' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('datetimepicker/css/bootstrap-datetimepicker.min.css');
            })->apply('CssMin');

            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('assets/packages/datetimepicker/js/moment.js');
                $collection->javascript('assets/packages/datetimepicker/js/bootstrap-datetimepicker.min.js');
                $collection->javascript('assets/init/datetimepicker.js');
            })->apply('JsMin');
        },
        'package_signatures' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('signatures/css/signature-pad.css');
            })->apply('CssMin');

            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('assets/packages/signatures/js/signature_pad.js');
                $collection->javascript('assets/init/signatures.js');
            })->apply('JsMin');
        },
        'package_parsley' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('parsley/parsley.css');
            })->apply('CssMin');

            $collection->directory('assets', function($collection)
            {
                $collection->javascript('assets/packages/parsley/parsley.min.js');
            })->apply('JsMin');
        },
        'package_calendar' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->stylesheet('calendar/bootstrap_calendar.css');
            })->apply('CssMin');

            $collection->directory('assets', function($collection)
            {
                $collection->javascript('js/calendar/bootstrap_calendar.js');
                $collection->javascript('init/calendar-init.js');
            })->apply('JsMin');
        },

        'package_fullcalendar' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->stylesheet('fullcalendar/fullcalendar.css');
                $collection->stylesheet('fullcalendar/theme.css');
            })->apply('CssMin');
            $collection->directory('assets', function($collection)
            {
                $collection->javascript('js/fullcalendar/jquery-ui.custom.min.js');
                $collection->javascript('js/fullcalendar/moment.min.js');
                //$collection->javascript('js/fullcalendar/moment-timezone.js');
                $collection->javascript('js/fullcalendar/fullcalendar.min.js');
                $collection->javascript('init/fullcalendar-init.js');
            })->apply('JsMin');
        },

        'package_chartsflot' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('/charts/flot/jquery.flot.min.js');
                $collection->javascript('/charts/flot/jquery.flot.spline.js');
                $collection->javascript('/charts/flot/jquery.flot.tooltip.min.js');
                $collection->javascript('/charts/flot/jquery.flot.grow.js');
                $collection->javascript('/charts/flot/jquery.flot.time.js');
            })->apply('JsMin');
        },

        'package_chartspie' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('/charts/easypiechart/jquery.easy-pie-chart.js');
                $collection->javascript('/charts/flot/jquery.flot.min.js');
                $collection->javascript('/charts/flot/jquery.flot.pie.min.js');
            })->apply('JsMin');
        },

        'package_chartsandpie' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('/charts/flot/jquery.flot.min.js');
                $collection->javascript('/charts/flot/jquery.flot.spline.js');
                $collection->javascript('/charts/flot/jquery.flot.pie.min.js');
                $collection->javascript('/charts/flot/jquery.flot.tooltip.min.js');
                $collection->javascript('/charts/flot/jquery.flot.grow.js');
                $collection->javascript('/charts/flot/jquery.flot.time.js');
                $collection->javascript('/charts/easypiechart/jquery.easy-pie-chart.js');
            })->apply('JsMin');
        },

        'package_googlemap' => function($collection)
        {
            $collection->directory('assets/init', function($collection)
            {
                $collection->javascript('gmaps.loader.js');
            })->apply('JsMin');
        },
        'package_gmap_form_init' => function($collection)
        {
            $collection->directory('assets/init', function($collection)
            {
                $collection->javascript('form.gmap.init.js');
            })->apply('JsMin');
        },
        'package_tokenfield' => function($collection)
         {
            $collection->directory('assets', function($collection)
            {
                $collection->stylesheet('js/tokenfield/css/bootstrap-tokenfield.css');
                $collection->stylesheet('js/tokenfield/css/tokenfield-typeahead.css');
            })->apply('CssMin');
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('tokenfield/js/bootstrap-tokenfield.js');
                $collection->javascript('tokenfield/js/typeahead.bundle.js');


            })->apply('JsMin');
         },
        'package_notes' => function($collection)
         {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('libs/underscore-min.js');
                $collection->javascript('libs/backbone-min.js');
                $collection->javascript('libs/backbone.localStorage-min.js');
                $collection->javascript('js/libs/moment.min.js');
                $collection->javascript('apps/notes.js');
            })->apply('JsMin');
         },
        'package_colorpalete' => function($collection)
        {
            $collection->directory('assets', function($collection)
            {
                $collection->stylesheet('js/colorpalete/css/bootstrap-colorpalette.css');

            })->apply('CssMin');
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('colorpalete/js/bootstrap-colorpalette.js');
            })->apply('JsMin');
        },
        'package_nestable' => function($collection)
        {
            $collection->directory('assets', function($collection)
            {
                $collection->stylesheet('js/nestablesortable/nestable.css');

            })->apply('CssMin');
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('nestablesortable/jquery.nestable.js');
            })->apply('JsMin');
        },
        'package_sortable' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('nestablesortable/jquery.sortable.js');
            })->apply('JsMin');
        },
        'package_nestablesortable' => function($collection)
        {
            $collection->directory('assets/js', function($collection)
            {
                $collection->stylesheet('nestablesortable/nestable.css');
            })->apply('UriRewriteFilter')->apply('CssMin');

            $collection->directory('assets', function($collection)
            {
                $collection->javascript('js/nestablesortable/jquery.sortable.js');
                $collection->javascript('js/nestablesortable/jquery.nestable.js');
                $collection->javascript('init/nestablesortable.js');
            })->apply('JsMin');
        },
        'package_editable' => function($collection)
        {
            $collection->directory('assets', function($collection)
            {
                $collection->stylesheet('js/editable/css/bootstrap-editable.css');

            })->apply('CssMin');
            $collection->directory('assets/js', function($collection)
            {
                $collection->javascript('editable/js/bootstrap-editable.js');
                $collection->javascript('assets/init/editable.js');
            })->apply('JsMin');
        },
        'package_summernote' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('summernote/dist/summernote.css');
            })->apply('CssMin');

            $collection->directory('assets', function($collection)
            {
                $collection->javascript('packages/summernote/dist/summernote.min.js');
                $collection->javascript('init/summernote.js');
            })->apply('JsMin');
        },
        'package_imagecrop' => function($collection)
        {
            $collection->directory('assets/packages', function($collection)
            {
                $collection->stylesheet('imagecrop/crop.css');
                $collection->stylesheet('imagecrop/canvas_example/example.css');
            })->apply('CssMin');
            $collection->directory('assets', function($collection)
            {
                $collection->javascript('packages/imagecrop/crop.js');
                $collection->javascript('assets/init/imagecrop.js');
            })->apply('JsMin');
        },
        'package_uploadify' => function($collection)
            {
                $collection->directory('assets/packages/uploadify', function($collection)
                {
                    $collection->stylesheet('uploadify.css');
                })->apply('CssMin')->apply('UriRewriteFilter');
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/uploadify/jquery.uploadify.min.js');
                })->apply('JsMin');
            },
        'package_uploadifive' => function($collection)
            {
                $collection->directory('assets/packages/uploadifive', function($collection)
                {
                    $collection->stylesheet('uploadifive.css');
                })->apply('CssMin')->apply('UriRewriteFilter');
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/uploadifive/jquery.uploadifive.js');
                })->apply('JsMin');
            },
        'package_ajaximageupload' => function($collection)
            {
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/ajaximageupload/upload.min.js');
                    $collection->javascript('packages/ajaximageupload/swfobject.js');
                    $collection->javascript('assets/init/ajaximageupload.js');
                })->apply('JsMin');
            },
        'package_masonry' => function($collection)
            {
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/masonry/masonry.js');
                })->apply('JsMin');
            },
        'package_isotope' => function($collection)
            {
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/isotope/isotope.js');
                    $collection->javascript('packages/isotope/imageloaded.js');
                })->apply('JsMin');
            },
        'package_gallery' => function($collection)
            {
                $collection->directory('assets', function($collection)
                {
                    $collection->javascript('packages/gallery/gallery.js');
                })->apply('JsMin');

            },
        'package_formwizard' => function($collection)
            {
                $collection->directory('assets/js/wizard', function($collection)
                {
                    $collection->javascript('jquery.bootstrap.wizard.js');
                    $collection->javascript('demo.js');
                })->apply('JsMin');
            },
        'package_select2' => function($collection)
        {
            $collection->directory('/assets', function($collection)
            {
                $collection->javascript('js/select2/js/select2.full.min.js');
            })->apply('JsMin');
            $collection->directory('/assets/js', function($collection)
            {
                $collection->stylesheet('select2/css/select2.css');
            })->apply('CssMin');
        },
        'package_fileinput' => function($collection)
        {
            $collection->directory('assets/js/file-input', function($collection)
            {
                $collection->javascript('bootstrap.file-input.js');
                $collection->javascript('bootstrap-filestyle.min.js');
            })->apply('JsMin');
        },
    ),


    /*
    |--------------------------------------------------------------------------
    | Production Environment
    |--------------------------------------------------------------------------
    |
    | Basset needs to know what your production environment is so that it can
    | respond with the correct assets. When in production Basset will attempt
    | to return any built collections. If a collection has not been built
    | Basset will dynamically route to each asset in the collection and apply
    | the filters.
    |
    | The last method can be very taxing so it's highly recommended that
    | collections are built when deploying to a production environment.
    |
    | You can supply an array of production environment names if you need to.
    |
    */

    'production' => array('production', 'prod' ,'_app'),

    /*
    |--------------------------------------------------------------------------
    | Build Path
    |--------------------------------------------------------------------------
    |
    | When assets are built with Artisan they will be stored within a directory
    | relative to the public directory.
    |
    | If the directory does not exist Basset will attempt to create it.
    |
    */

    'build_path' => 'builds',

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Enable debugging to have potential errors or problems encountered
    | during operation logged to a rotating file setup.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | Node Paths
    |--------------------------------------------------------------------------
    |
    | Many filters use Node to build assets. We recommend you install your
    | Node modules locally at the root of your application, however you can
    | specify additional paths to your modules.
    |
    */

    'node_paths' => array(

        base_path().'/node_modules'

    ),

    /*
    |--------------------------------------------------------------------------
    | Gzip Built Collections
    |--------------------------------------------------------------------------
    |
    | To get the most speed and compression out of Basset you can enable Gzip
    | for every collection that is built via the command line. This is applied
    | to both collection builds and development builds.
    |
    | You can use the --gzip switch for on-the-fly Gzipping of collections.
    |
    */

    'gzip' => true,

    /*
    |--------------------------------------------------------------------------
    | Asset and Filter Aliases
    |--------------------------------------------------------------------------
    |
    | You can define aliases for commonly used assets or filters.
    | An example of an asset alias:
    |
    |   'layout' => 'stylesheets/layout/master.css'
    |
    | Filter aliases are slightly different. You can define a simple alias
    | similar to an asset alias.
    |
    |   'YuiCss' => 'Yui\CssCompressorFilter'
    |
    | However if you want to pass in options to an aliased filter then define
    | the alias as a nested array. The key should be the filter and the value
    | should be a callback closure where you can set parameters for a filters
    | constructor, etc.
    |
    |   'YuiCss' => array('Yui\CssCompressorFilter', function($filter)
    |   {
    |       $filter->setArguments('path/to/jar');
    |   })
    |
    |
    */

    'aliases' => array(

        'assets' => array(),

        'filters' => array(

            /*
            |--------------------------------------------------------------------------
            | Less Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter is applied only when asset has a ".less" extension and it will
            | attempt to find missing constructor arguments.
            |
            */

            'Less' => array('LessFilter', function($filter)
            {
                $filter->whenAssetIs('.*\.less')->findMissingConstructorArgs();
            }),

            /*
            |--------------------------------------------------------------------------
            | Sass Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter is applied only when asset has a ".sass" or ".scss" extension and
            | it will attempt to find missing constructor arguments.
            |
            */

            'Sass' => array('Sass\ScssFilter', function($filter)
            {
                $filter->whenAssetIs('.*\.(sass|scss)')->findMissingConstructorArgs();
            }),

            /*
            |--------------------------------------------------------------------------
            | CoffeeScript Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter is applied only when asset has a ".coffee" extension and it will
            | attempt to find missing constructor arguments.
            |
            */

            'CoffeeScript' => array('CoffeeScriptFilter', function($filter)
            {
                $filter->whenAssetIs('.*\.coffee')->findMissingConstructorArgs();
            }),

            /*
            |--------------------------------------------------------------------------
            | CssMin Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter is applied only when within the production environment and when
            | the "CssMin" class exists.
            |
            */

            'CssMin' => array('CssMinFilter', function($filter)
            {
                $filter->whenAssetIsStylesheet()->whenProductionBuild()->whenClassExists('CssMin');
            }),

            /*
            |--------------------------------------------------------------------------
            | JsMin Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter is applied only when within the production environment and when
            | the "JsMin" class exists.
            |
            */

            'JsMin' => array('JSMinFilter', function($filter)
            {
                $filter->whenAssetIsJavascript()->whenProductionBuild()->whenClassExists('JSMin');
            }),

            /*
            |--------------------------------------------------------------------------
            | UriRewrite Filter Alias
            |--------------------------------------------------------------------------
            |
            | Filter gets a default argument of the path to the public directory.
            |
            */

            'UriRewriteFilter' => array('UriRewriteFilter', function($filter)
            {
                $filter->setArguments(public_path())->whenAssetIsStylesheet();
            })
        )
    )
);