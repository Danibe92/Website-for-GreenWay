<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Companies Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        #companySearch {
            padding: 8px;
            width: 100%;
            margin-bottom: 20px;
        }

        #topCompanies {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        ol {
            list-style-type: none;
            padding: 0;
        }

        li {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <label for="companySearch">Search for Company:</label>
    <input id="companySearch">
    <div id="topCompanies"></div>
    <script>
        $(document).ready(function () {
            <?php

            // Leggi il contenuto del file JSON
            $jsonData = file_get_contents('aziende.json');

            // Decodifica il JSON in un array associativo
            $data = json_decode($jsonData, true);

            // Ordina l'array in base al valore delle aziende
            usort($data, function ($a, $b) {
                return $a['kitrovare'] < $b['kitrovare'];
            });

            // Visualizza la classifica
            echo "var companyList = '<h2>Classifica delle aziende:</h2><ol>';";
            foreach ($data as $index => $company) {
                echo "companyList += '<li>Nome: " . $company['name'] . " - Valore: " . $company['kitrovare'] ."-Iscritti: ".$company["iscritti"] ."</li>';";
            }
            echo "companyList += '</ol>';";
            ?>

            $("#topCompanies").html(companyList);

            var companyNamesForAutocomplete = <?php echo json_encode(array_column($data, 'name')); ?>;

            $("#companySearch").autocomplete({
                source: function (request, response) {
                    var results = $.ui.autocomplete.filter(companyNamesForAutocomplete, request.term);
                    response(results.slice(0, 10)); // Display a maximum of 10 results
                },
                autoFocus: true, // Automatically focus on the first result
                minLength: 1 // Show suggestions after typing 1 character
            });
        });

    </script>
</body>

</html>