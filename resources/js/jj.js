require("./bootstrap");
//require("./apt");
var $ = require("jquery");
const Handlebars = require("handlebars");
const { log } = require("handlebars");
const { find } = require("lodash");
const APPLICATION_NAME = "My Application";
const APPLICATION_VERSION = "1.0";
tt.setProductInfo(APPLICATION_NAME, APPLICATION_VERSION);
const apiKey = "31kN4urrGHUYoJ4IOWdAiEzMJJKQpfVk";

let map = tt.map({
    key: apiKey,
    container: "map",
    center: [12.49334, 41.88996],
    style: "tomtom://vector/1/basic-main",
    zoom: 4
});

//// eventi che fanno paritre la ricerca
$(document).ready(function() {
    var instantSearch = (function() {
        if ($("#address-inst").html() != "") {
            //getcoordinates prende l'adress il range e un boolean per sapere se vuoi filtrare per servizi
            getCoordinates(
                $("#address-inst").html(),
                $("#range-form").html(),
                false
            );
            getServices();
        }
    })();

    $(".nav__search-icon-big").click(function() {
        $(".search__resoults__apartment-cards").empty();
        $(".search__resoults__apartment-cards.sponsor").empty();
        if ($("#search").val() != "") {
            getCoordinates($("#search").val(), $("#range-value").html(), false);
            $("#address-inst").text($("#search").val());
        }
    });

    $("#search").keydown(function() {
        if (event.which == 13 || event.keyCode == 13) {
            if ($("#search").val() != "") {
                getCoordinates(
                    $("#search").val(),
                    $("#range-value").html(),
                    false
                );
            }
        }
    });
});
//// prendi coordinate dell'input////////////////
function getCoordinates(input, range, serviceFilter) {
    var service = serviceFilter;
    var zoom = 10;
    if (input != "") {
        tt.services
            .fuzzySearch({
                key: apiKey,
                query: input
            })
            .go()
            .then(function(response) {
                var longitude = response.results[0].position["lng"];
                var latitude = response.results[0].position["lat"];
                city = response.results[0].address["municipality"];
                streetName = response.results[0].address["streetName"];
                // condizione per selezionare lo zoom in caso di città o indirizzo specifico
                if (streetName != undefined && city) {
                    zoom = 16;
                }
                map = tt.map({
                    key: apiKey,
                    style: "tomtom://vector/1/basic-main",
                    container: "map",
                    center: response.results[0].position,
                    zoom: zoom
                });
                if (service == false) {
                    getSponsored(latitude, longitude, range, 1);
                    getCards(latitude, longitude, range, 0);
                } else {
                    getFilter(latitude, longitude, range, 1, service);
                    getFilter(latitude, longitude, range, 0, service);
                }
            });
    }
}

/////////// chiamata all nostro db che richiama funzione handlebars

function getSponsored(lat, lng, maxDist, sponsor) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/apartments",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            lat: lat,
            lng: lng,
            maxDist: maxDist,
            sponsored: sponsor
        },
        success: function(risposta) {
            if (risposta.length > 0) {
                compileHandlebars(risposta, sponsor);
            }
        },
        error: function() {}
    });
}
function getCards(lat, lng, maxDist, sponsor) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/apartments",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            lat: lat,
            lng: lng,
            maxDist: maxDist,
            sponsor: sponsor
        },
        success: function(risposta) {
            if (risposta.length > 0) {
                compileHandlebars(risposta, sponsor);
            }
        },
        error: function() {}
    });
}
///////////////////////////////
///////////////////////////
function getFilter(lat, lng, maxDist, sponsor, services) {
    var 
    $.ajax({
        url: "http://127.0.0.1:8000/api/apartments",
        method: "GET",
        headers: {
            KEY: "test"
        },
        data: {
            lat: lat,
            lng: lng,
            maxDist: maxDist,
            services: Array.from(services),
            sponsor: sponsor
        },
        
        success: function(risposta) {
            console.log(risposta);
            if (risposta.length > 0) {
                compileHandlebars(risposta, sponsor);
            }
        },
        error: function() {}
    });
}
////////////////////////////////////
/// funzione per inserire le card della ricerca nel dom e creare i marker associati nella mappa
function compileHandlebars(risp, sponsor) {
    var containerCards = "";
    if (sponsor == 1) {
        containerCards = $("#sponsor");
    } else {
        containerCards = $("#no-sponsor");
    }
    var source = $("#handlebars_cards").html();
    var templateCards = Handlebars.compile(source);
    const markersCity = [];
    for (let i = 0; i < risp.length; i++) {
        var context = {
            city: risp[i].city,
            title: troncaStringa(risp[i].title),
            id: `<input class="aps_id" type="hidden" data-sponsor="${sponsor}" name="apartment_id" value=${risp[i].id}>`,
            sponsor: sponsor
        };

        var coordinates = [risp[i].longitude, risp[i].latitude];
        var address = risp[i].address;
        var city = risp[i].city;
        var price = risp[i].daily_price;
        // creo il custom marker
        var element = document.createElement("div");
        element.id = "marker";
        const marker = new tt.Marker({ element: element })
            .setLngLat(coordinates)
            .setPopup(new tt.Popup({ offset: 35 }).setHTML(address))
            .addTo(map);

        var popupOffsets = {
            top: [0, 0],
            bottom: [0, -70],
            "bottom-right": [0, -70],
            "bottom-left": [0, -70],
            left: [25, -35],
            right: [-25, -35]
        };

        // popup sui marker
        var popup = new tt.Popup({
            offset: popupOffsets
        }).setHTML(
            address +
                " " +
                city +
                " " +
                "<br>" +
                "<strong>" +
                price +
                "</strong>" +
                " € a notte"
        );

        // assegno il popup
        marker.setPopup(popup);

        var htmlContext = templateCards(context);

        containerCards.append(htmlContext);
        appendServices(risp[i].id);
        getImages(risp[i].id, sponsor);
        var el = $(".search__resoults__apartment-cards-content");
        var details = buildLocation(el, address);
        // cliccando su un elemento della lista a sx lo trova in mappa
        details.on(
            "click",
            (function(marker) {
                const activeItem = $(this);
                return function() {
                    map.easeTo({
                        center: marker.getLngLat(),
                        zoom: 16
                    });
                    // serve a passare da un marker all'altro nella selezione di sx
                    closeAllPopups();
                    // marker.togglePopup();
                };
            })(marker)
        );

        // cliccando sul marker aggiunge la classe selected alla card dell'appartamento corrispondente
        marker._element.addEventListener("click", function() {
            var posizione = $(this).index() - 1;
            details.removeClass("selected");
            details.eq(posizione).addClass("selected");
        });
    }
    setServices();
}
/// appende i servizi selezionabili

/// appendere le immagini allo slider
function getImages(id, sponsor) {
    $.ajax({
        url: "http://127.0.0.1:8000/api/images",
        method: "GET",

        data: {
            id: id
        },
        headers: {
            KEY: "test"
        },
        success: function(response) {
            for (var i = 0; i < response.length; i++) {
                var clss = "hidden";
                if (i == 0) {
                    clss = "first active";
                } else if (i == response.length - 1) {
                    clss = "hidden last";
                } else {
                    clss = "hidden";
                }
                appendImages(response[i], clss, sponsor);
                
            }
        },
        error: function() {}
    });
}
function appendImages(risp, clss, sponsor) {
    var container = $(
        ".search__resoults__apartment-cards-content.sponsor-" + sponsor
    );
    container.each(function() {
        appId = $(this).find(".aps_id").val();
        if (appId == risp.apartment_id) {
            img = `<img class="search__resoults__apartment-cards-content-slider-img apt-image ${clss}" 
           src="${risp.path}">`;
            $(this)
                .find(".search__resoults__apartment-cards-content-slider")
                .append(img);
        }
    });
}
// funzione per troncare una stringa
function troncaStringa(stringa) {
    var shortText = stringa;
    if (stringa.length > 28) {
        for (var i = 28; i > 0; i--) {
            if (stringa[i] == " ") {
                shortText = stringa.substring(0, i) + "...";
                i = 0;
            }
        }
    }
    return shortText;
}

/// filtra ricerca per servizi
var serviceCheck = (function() {
    var selectedService = [];
    $(document).on("click", ".services-all", function() {
        var serviceType = parseInt($(this).attr("data-servicetype"));

        $(this).toggleClass("service-selected");
        if (
            selectedService.length < $(".services-all").length &&
            !selectedService.includes(serviceType)
        ) {
            selectedService.push(serviceType);
        }
        if (!$(this).hasClass("service-selected")) {
            selectedService = selectedService.filter(function(item) {
                return item !== serviceType;
            });
        }
        
    });
    $("#cerca-filtri").click(function() {
        $(".search__resoults__apartment-cards").empty();
        $(".search__resoults__apartment-cards.sponsor").empty();
        getCoordinates($("#address-inst").text(),$("#range-value").html(),selectedService
        );
    });
})();