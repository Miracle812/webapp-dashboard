/*
*  Altair Admin
*  @version v2.4.0
*  @author tzd
*  @license http://themeforest.net/licenses
*  plugins_vector_maps.js - plugins_vector_maps.html
*/

$(function() {
    // vector maps
    altair_vector_maps.world_connected();
    altair_vector_maps.usa_legends();
});

altair_vector_maps = {
    world_connected: function() {
        $("#vmaps_world_connected").mapael({
            map: {
                name: "world_countries",
                defaultArea: {
                    attrs: {
                        fill: "#eee",
                        stroke: "#888",
                        'stroke-width': 0.1
                    },
                    attrsHover: {
                        fill: "#B0BEC5"
                    }
                },
                // Default attributes can be set for all links
                defaultLink: {
                    factor: 0.4,
                    attrsHover: {
                        stroke: "#B71C1C"
                    }
                },
                defaultPlot: {
                    text: {
                        attrs: {
                            'font-size': 12,
                            fill: "#222"
                        },
                        attrsHover: {
                            fill: "#222"
                        }
                    },
                    attrsHover: {
                        stroke: "#222",
                        'stroke-width': 2
                    },
                    size: 10
                }
            },
            plots: {
                'paris': {
                    latitude: 48.86,
                    longitude: 2.3444,
                    tooltip: {
                        content: "Paris<br /><span class='uk-text-small'>Population: 2,211,297</span>"
                    }
                },
                'newyork': {
                    latitude: 40.667,
                    longitude: -73.833,
                    tooltip: {
                        content: "New york<br /><span class='uk-text-small'>Population: 8,491,079</span>"
                    }
                },
                'sanfrancisco': {
                    latitude: 37.792032,
                    longitude: -122.394613,
                    tooltip: {
                        content: "San Francisco"
                    }
                },
                'brasilia': {
                    latitude: -15.781682,
                    longitude: -47.924195,
                    tooltip: {
                        content: "Brasilia<br /><span class='uk-text-small'>Population: 204,465,000</span>"
                    }
                },
                'roma': {
                    latitude: 41.827637,
                    longitude: 12.462732,
                    tooltip: {
                        content: "Roma"
                    }
                },
                'miami': {
                    latitude: 25.789125,
                    longitude: -80.205674,
                    tooltip: {
                        content: "Miami"
                    }
                },
                'tokyo': {
                    latitude: 35.687418,
                    longitude: 139.692306,
                    text: {
                        content: 'Tokyo'
                    },
                    attrs: {
                        fill: "#FF6F00"
                    }
                },
                'sydney': {
                    latitude: -33.917,
                    longitude: 151.167,
                    text: {
                        content: 'Sydney'
                    },
                    attrs: {
                        fill: "#FF6F00"
                    }
                },
                'plot1': {
                    latitude: 22.906561,
                    longitude: 86.840170,
                    text: {
                        content: 'Plot1',
                        position: 'left',
                        margin: 5
                    },
                    attrs: {
                        fill: "#FF6F00"
                    }
                },
                'plot2': {
                    latitude: -0.390553,
                    longitude: 115.586762,
                    text: {
                        content: 'Plot2',
                        position: 'top'
                    },
                    attrs: {
                        fill: "#FF6F00"
                    }
                },
                'plot3': {
                    latitude: 44.065626,
                    longitude: 94.576079,
                    text: {
                        content: 'Plot3'
                    },
                    attrs: {
                        fill: "#FF6F00"
                    }
                }
            },
            // Links allow you to connect plots between them
            links: {
                'link1': {
                    factor: -0.3,
                    // The source and the destination of the link can be set with a latitude and a longitude or a x and a y ...
                    between: [
                        {
                            latitude: 24.708785,
                            longitude: -5.402427
                        }, {
                            x: 560,
                            y: 280
                        }
                    ],
                    attrs: {
                        "stroke-width": 2
                    },
                    tooltip: {
                        content: "Link"
                    }
                },
                'parisnewyork': {
                    // ... Or with IDs of plotted points
                    factor: -0.3,
                    between: ['paris', 'newyork'],
                    attrs: {
                        "stroke-width": 2
                    },
                    tooltip: {
                        content: "Paris - New-York"
                    }
                },
                'parissanfrancisco': {
                    // The curve can be inverted by setting a negative factor
                    factor: -0.5,
                    between: ['paris', 'sanfrancisco'],
                    attrs: {
                        "stroke-width": 4
                    },
                    tooltip: {
                        content: "Paris - San-Francisco"
                    }
                },
                'parisbrasilia': {
                    factor: -0.8,
                    between: ['paris', 'brasilia'],
                    attrs: {
                        "stroke-width": 1
                    },
                    tooltip: {
                        content: "Paris - Brasilia"
                    }
                },
                'romamiami': {
                    factor: 0.2,
                    between: ['roma', 'miami'],
                    attrs: {
                        "stroke-width": 4
                    },
                    tooltip: {
                        content: "Roma - Miami"
                    }
                },
                'sydneyplot1': {
                    factor: -0.2,
                    between: ['sydney', 'plot1'],
                    attrs: {
                        stroke: "#FFA000",
                        "stroke-width": 3,
                        "stroke-linecap": "round"
                    },
                    tooltip: {
                        content: "Sydney - Plot1"
                    }
                },
                'sydneyplot2': {
                    factor: -0.1,
                    between: ['sydney', 'plot2'],
                    attrs: {
                        stroke: "#FFA000",
                        "stroke-width": 6,
                        "stroke-linecap": "round"
                    },
                    tooltip: {
                        content: "Sydney - Plot2"
                    }
                },
                'sydneyplot3': {
                    factor: 0.2,
                    between: ['sydney', 'plot3'],
                    attrs: {
                        stroke: "#FFA000",
                        "stroke-width": 4,
                        "stroke-linecap": "round"
                    },
                    tooltip: {
                        content: "Sydney - Plot3"
                    }
                },
                'sydneytokyo': {
                    factor: 0.2,
                    between: ['sydney', 'tokyo'],
                    attrs: {
                        stroke: "#FFA000",
                        "stroke-width": 6,
                        "stroke-linecap": "round"
                    },
                    tooltip: {
                        content: "Sydney - Plot2"
                    }
                }
            }
        });
    },
    usa_legends: function() {
        var marker_url = isHighDensity() ? 'assets/img/md-images/ic_place_black_48dp.png' : 'assets/img/md-images/ic_place_black_24dp.png',
            marker_url_beenhere = isHighDensity() ? 'assets/img/md-images/ic_beenhere_black_48dp.png' : 'assets/img/md-images/ic_beenhere_black_24dp.png';

        $('#vmaps_usa_legend').mapael({
            map: {
                name: "usa_states",
                defaultArea: {
                    attrs: {
                        fill: "#eee",
                        stroke: "#888",
                        'stroke-width': 0.1
                    },
                    attrsHover: {
                        fill: "#8BC34A"
                    }
                }
            },
            legend: {
                plot: {
                    mode : "horizontal",
                    title: "American cities",
                    slices: [{
                        label: "Value 1",
                        sliceValue: "Value 1",
                        type: "image",
                        url: marker_url,
                        width: isHighDensity() ? 48 : 24,
                        height: isHighDensity() ? 48 : 24,
                        attrsHover: {
                            transform: "s1.4"
                        }
                    }, {
                        label: "Value 2",
                        sliceValue: "Value 2",
                        type: "image",
                        url: marker_url_beenhere,
                        width: isHighDensity() ? 48 : 24,
                        height: isHighDensity() ? 48 : 24,
                        attrsHover: {
                            transform: "s1.4"
                        }
                    }]
                }
            },
            plots: {
                'ny': {
                    latitude: 40.717079,
                    longitude: -74.00116,
                    tooltip: {content: "New York"},
                    value: "Value 1"
                },
                'an': {
                    latitude: 61.2108398,
                    longitude: -149.9019557,
                    tooltip: {content: "Anchorage"},
                    value: "Value 2"
                },
                'sf': {
                    latitude: 37.792032,
                    longitude: -122.394613,
                    tooltip: {content: "San Francisco"},
                    value: "Value 1"
                },
                'pa': {
                    latitude: 19.493204,
                    longitude: -154.8199569,
                    tooltip: {content: "Pahoa"},
                    value: "Value 2"
                },
                'la': {
                    latitude: 34.025052,
                    longitude: -118.192006,
                    tooltip: {content: "Los Angeles"},
                    value: "Value 1"
                },
                'dallas': {
                    latitude: 32.784881,
                    longitude: -96.808244,
                    tooltip: {content: "Dallas"},
                    value: "Value 2"
                },
                'miami': {
                    latitude: 25.789125,
                    longitude: -80.205674,
                    tooltip: {content: "Miami"},
                    value: "Value 2"
                },
                'washington': {
                    latitude: 38.905761,
                    longitude: -77.020746,
                    tooltip: {content: "Washington"},
                    value: "Value 2"
                },
                'seattle': {
                    latitude: 47.599571,
                    longitude: -122.319426,
                    tooltip: {content: "Seattle"},
                    value: "Value 1"
                }
            }
        });
    }
};
