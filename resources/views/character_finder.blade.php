<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & JQUERY-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>Character Finder</title>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
        h1 {
            margin: 1em 0;
            font-size: 1.3em;
        }
        @media (max-width: 768px) {
            .col-sm-12 {
                margin-top: 1em;
            }
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <h1 class="text-center">Human Finder</h1>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8 col-sm-12">
                    <input class="form-control" id="name" aria-describedby="name" placeholder="Search by random string">
                </div>
                <div class="col-md-4 col-sm-12">
                    <button id="search-button" type="button" class="btn btn-outline-primary">SEARCH</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <select id="hair-color-select" class="form-select" aria-label="Hair color selector">
                        <option selected>Select hair color</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select id="skin-color-select" class="form-select" aria-label="Skin color selector">
                        <option selected>Select skin color</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12">
                    <button id="apply-filters-button" type="button" class="btn btn-outline-primary">APPLY FILTERS</button>
                </div>
            </div>
        </div>
    </div>

    <div id="no-results-message" class="alert alert-warning" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i> Your search doesn't match any results.
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <a href="{{ url('/') }}">Enunciado</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- Script -->
    <script type='text/javascript'>

       $('#search-button').on('click', function() {

    var searchText = $('#name').val();
    var hairColor = $('#hair-color-select').val();
    var skinColor = $('#skin-color-select').val();


    $.ajax({
        url: 'https://swapi.dev/api/people/', // URL base para obtener todos los personajes
        method: 'GET',
        data: {
            search: searchText,
            hair_color: hairColor,
            skin_color: skinColor,
            species: 'https://swapi.dev/api/species/1/'
        },
        success: function(data) {

            $('tbody').empty();

             if (data.results.length === 0) {

                    $('#no-results-message').show();
                    $('table').hide();
                } else {

                    $('#no-results-message').hide();
                    $('table').show();
                }


            for (var i = 0; i < data.results.length; i++) {
                var character = data.results[i];
                var row = '<tr>';
                row += '<td>' + character.name + '</td>';
                row += '<td>' + 'Details here' + '</td>';
                row += '</tr>';
                $('tbody').append(row);
            }
        },
        error: function() {

            alert('Error en la búsqueda');
        }


    });
});

    ////////////////////////////////////////////////////////////////////Selectores
      $.ajax({
    url: 'https://swapi.dev/api/species/1/',
    method: 'GET',
    success: function (data) {
        var hairColors = data.hair_colors.split(',').map(function(color) {
            return color.trim();
        });

        var hairColorSelect = document.getElementById('hair-color-select');
        for (var i = 0; i < hairColors.length; i++) {
            var option = document.createElement('option');
            option.text = hairColors[i];
            hairColorSelect.appendChild(option);
        }

        var skinColors = data.skin_colors.split(',').map(function(color) {
            return color.trim();
        });

        var skinColorSelect = document.getElementById('skin-color-select');
        for (var i = 0; i < skinColors.length; i++) {
            var option = document.createElement('option');
            option.text = skinColors[i];
            skinColorSelect.appendChild(option);
        }
    },
    error: function () {
        alert('Error al obtener datos de la especie humana');
    }
});

    //////////////////

    $('#apply-filters-button').on('click', function () {
    var hairColor = $('#hair-color-select').val();
    var skinColor = $('#skin-color-select').val();


    $.ajax({
        url: 'https://swapi.dev/api/species/1/', // URL de la especie humana en SWAPI
        method: 'GET',
        success: function (data) {
            var humanCharacters = data.people;

            $('tbody').empty();

            function addCharacterToTable(characterURL) {
                $.ajax({
                    url: characterURL,
                    method: 'GET',
                    success: function (characterData) {
                        if (
                            (hairColor === 'Any' || characterData.hair_color.toLowerCase() === hairColor.toLowerCase()) &&
                            (skinColor === 'Any' || characterData.skin_color.toLowerCase() === skinColor.toLowerCase())
                        ) {
                            var row = '<tr>';
                            row += '<td>' + characterData.name + '</td>';
                            row += '<td>' + 'Details here' + '</td>';
                            row += '</tr>';
                            $('tbody').append(row);
                        }
                    },
                    error: function () {
                        alert('Error al obtener datos de personaje');
                    },
                });
            }

            // Itera sobre los personajes y agrega a la tabla los que cumplan con los filtros
            for (var i = 0; i < humanCharacters.length; i++) {
                addCharacterToTable(humanCharacters[i]);
            }

            if ($('tbody').is(':empty')) {
                $('#no-results-message').show();
                $('table').hide();
            } else {
                $('#no-results-message').hide();
                $('table').show();
            }
        },
        error: function () {
            alert('Error en la búsqueda');
        },
    });
});


    </script>
</div>
</body>
</html>
