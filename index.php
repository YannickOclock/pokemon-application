<?php
    // Récupération des données sur une API

    $pokemonNumber = 1;
    
    if(isset($_GET['page'])) {
        $pokemonNumber = $_GET['page'];
    }

    $url = "http://localhost:5000/api/v1/pokemon/$pokemonNumber";
    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);

    $results = json_decode($response);

    // Extraction des résultats dans des variables

    $pokemonNumber = str_pad($pokemonNumber, 4, '0', STR_PAD_LEFT);
    $pokemonName = $results->name->fr;
    
    $pokemonAvatar = $results->sprites->regular;

    $pokemonCategory = $results->category;
    $pokemonTalent = $results->talents[0]->name;
    $pokemonHeight = $results->height;
    $pokemonWeight = $results->weight;

    $pokemonDescription = "Description à venir ...";
    
    /* Si la description existe */
    if(isset($results->description)) {
        $pokemonDescription = $results->description;
    }

    // Recherche des types
    $pokemonTypes = array();
    foreach($results->types as $type) {
        $pokemonTypes[] = $type->name;
    }

    // Recherche des faiblesses
    $pokemonWeaknesses = array();
    foreach($results->resistances as $resistance) {
        if($resistance->multiplier == 2) {
            $pokemonWeaknesses[] = $resistance->name;
        }
    }

    // extraction des stats
    $hp = $results->stats->hp;
    $atk = $results->stats->atk;
    $def = $results->stats->def;
    $speAtk = $results->stats->spe_atk;
    $speDef = $results->stats->spe_def;
    $vit = $results->stats->vit;


    // Appel de la liste des pokémons pour récupérer le suivant et le précédent

    $url = "http://localhost:5000/api/v1/gen/1";
    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($client);
    $results = json_decode($response);

    /* valeur précédente par défaut */
    $pokemonPrevNumberPage = 0;
    $pokemonPrevNumber = '';
    $pokemonPrevName = "Inconnu";

    $pokemonNextNumberPage = 0;
    $pokemonNextNumber = '';
    $pokemonNextName = "Inconnu";

    foreach($results as $key => $result) {
        if($result->pokedexId == $pokemonNumber) {
            /* on a trouvé dans la liste le pokémon actuel */

            /* on cherche le précédent */
            if(isset($results[$key-1])) {
                $pokemonPrev = $results[$key-1];
                $pokemonPrevNumberPage = $pokemonPrev->pokedexId;
                $pokemonPrevNumber = str_pad($pokemonPrevNumberPage, 4, '0', STR_PAD_LEFT);
                $pokemonPrevName = $pokemonPrev->name->fr;
            }

            /* on cherche le suivant */
            if(isset($results[$key+1])) {
                $pokemonNext = $results[$key+1];
                $pokemonNextNumberPage = $pokemonNext->pokedexId;
                $pokemonNextNumber = str_pad($pokemonNextNumberPage, 4, '0', STR_PAD_LEFT);
                $pokemonNextName = $pokemonNext->name->fr;
            }
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pokemonName ?> | Pokédex</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
    <link href="https://db.onlinewebfonts.com/c/51ba22ae06790efd464bde752a2cd9d1?family=Flexo" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container">
        <section class="title">
            <a href="index.php?page=<?= $pokemonPrevNumberPage ?>" class="button">
                <div class="content">
                    <span class="pokemon-btn">
                        <i class="fa-solid fa-angle-left"></i>
                    </span>
                    <span class="pokemon-number">Nº <?= $pokemonPrevNumber ?></span>
                    <span class="pokemon-name"><?= $pokemonPrevName ?></span>
                </div>
            </a>
            <a href="index.php?page=<?= $pokemonNextNumberPage ?>" class="button">
                <div class="content">
                    <span class="pokemon-name"><?= $pokemonNextName ?></span>
                    <span class="pokemon-number">Nº <?= $pokemonNextNumber ?></span>
                    <span class="pokemon-btn">
                        <i class="fa-solid fa-angle-right"></i>
                    </span>
                </div>
            </a>
            <div class="pokemon-title-before"></div>
            <div class="pokemon-title">
                <div class="pokemon-name"><?= $pokemonName ?></div>
                <div class="pokemon-number">Nº <?= $pokemonNumber ?></div>
            </div>
            <div class="pokemon-title-after"></div>
        </section>
        <section class="pokemon-details">
            <div class="pokemon-details--content">
                <div class="pokemon-details--content-left">
                    <img src="<?= $pokemonAvatar ?>" alt="Carapuce" class="pokemon-avatar">
                    <div class="pokemon-stats-info">
                            <h3>Stats de base</h3>
                            <ul>
                                <li class="first">
                                    <ul class="gauge">
                                        <?php
                                            $hp = 20 - ($hp / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i <= $hp)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>PV</span>
                                </li>
                                <li>
                                    <ul class="gauge">
                                        <?php
                                            $atk = 20 - ($atk / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i <= $atk)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>Attaque</span>
                                </li>
                                <li>
                                    <ul class="gauge">
                                        <?php
                                            $def = 20 - ($def / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i <= $def)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>Défense</span>
                                </li>
                                <li>
                                    <ul class="gauge">
                                        <?php
                                            $speAtk = 20 - ($speAtk / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i <= $speAtk)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>Attaque Spéciale</span>
                                </li>
                                <li>
                                    <ul class="gauge">
                                        <?php
                                            $speDef = 20 - ($speDef / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i < $speDef)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>Défense Spéciale</span>
                                </li>
                                <li>
                                    <ul class="gauge">
                                        <?php
                                            $vit = 20 - ($vit / 100 * 20);
                                            for($i = 0; $i < 20; $i++) {
                                                if($i <= $vit)
                                                    echo "<li></li>";
                                                else
                                                    echo "<li class='stats--wide'></li>";
                                            }
                                        ?>
                                    </ul>
                                    <span>Vitesse</span>
                                </li>
                            </ul>
                    </div>
                </div>
                <div class="pokemon-details--content-right">
                    <p class="pokemon-details--description">
                        <?= $pokemonDescription ?>
                    </p>
                    <div class="pokemon-details--abilities">
                        <div class="pokemon-details--abilities-left">
                            <ul>
                                <li>
                                    <span class="attribute-title">Taille</span>
                                    <span class="attribute-value"><?= $pokemonHeight ?></span>
                                </li>
                                <li>
                                    <span class="attribute-title">Poids</span>
                                    <span class="attribute-value"><?= $pokemonWeight ?></span>
                                </li>
                                <li>
                                    <span class="attribute-title">Sexe</span>
                                    <span class="attribute-value">
                                        <i class="fa-solid fa-mars"></i>
                                        <i class="fa-sharp fa-solid fa-venus"></i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="pokemon-details--abilities-right">
                            <ul>
                                <li>
                                    <span class="attribute-title">Catégorie</span>
                                    <span class="attribute-value"><?= $pokemonCategory ?></span>
                                </li>
                                <li>
                                    <span class="attribute-title">Talent</span>
                                    <span class="attribute-value"><?= $pokemonTalent ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="pokemon-details--type">
                        <h3>Type</h3>
                        <ul>
                            <?php foreach($pokemonTypes as $pokemonType): ?>
                                <li class="bgcolor-grey"><?= $pokemonType ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="pokemon-details--weaknesses">
                        <h3>Faiblesses</h3>
                        <ul>
                            <?php foreach($pokemonWeaknesses as $pokemonWeakness): ?>
                                <li class="bgcolor-grey"><?= $pokemonWeakness ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>