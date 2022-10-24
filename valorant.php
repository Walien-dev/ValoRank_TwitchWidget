<?php
    // get $_GET valorant username and check valorant rank and level and return it
    if (!isset($_GET['name']) && !isset($_GET['tag'])) {
        ?>
            <title>ValoRank</title>
            <form action="" method="get">
                <label for="name">Quel est ton pseudo Valorant</label>
                <input type="text" id="name" name="name" placeholder="Pseudo">
                <label for="tag">Quel est ton tag Valorant</label>
                <input type="text" id="tag" name="tag" placeholder="Tag">
                <input type="hidden" name="alert" value="true">
                <input type="submit" value="Valider">
            </form>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #1b1b1b;
                    font-family: 'roboto', sans-serif;
                }
                form {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    background-color: #2b2b2b;
                    padding: 20px;
                    border-radius: 10px;
                }
                form label {
                    color: #fff;
                    font-size: 20px;
                    margin-bottom: 10px;
                }
                form input {
                    padding: 10px;
                    border-radius: 5px;
                    border: none;
                    margin-bottom: 10px;
                }
                form input[type="submit"] {
                    background-color: #1b1b1b;
                    color: #fff;
                    font-size: 20px;
                    cursor: pointer;
                }
            </style>
        <?php
    } else {
        $name = $_GET['name'];
        $tag = str_replace("#", "", $_GET['tag']);
        $url = "https://api.henrikdev.xyz/valorant/v1/mmr/eu/$name/$tag";
        // if the url is not valid or return an error 404
        if (get_headers($url)[0] == "HTTP/1.1 404 Not Found") {
            echo "Erreur, le pseudo et/ou le tag n'existe pas";
        } else {

            // if the user want to get an alert to get the url of this page
            if (isset($_GET['alert'])) {
                echo "<script>prompt('Copie ce lien à mettre dans ton OBS (format 100x100)', 'https://armice.fr/twitch_embeds/valorant?name=$name&tag=$tag')</script>";
            }

            $data = json_decode(file_get_contents($url), true);
            $rank = $data['data']['currenttierpatched'];
            $rr = $data['data']['ranking_in_tier'];
            $rank_img = $data['data']['images']['large'];

            if (isset($_GET['only_content'])) {
                echo "<img src='$rank_img' alt='Rank: $rank'>";
                echo "<span class='badge'>$rr LP</span>";
            } else {

                ?>
                    <title>ValoRank | <?= $name ?>#<?= $tag ?> (<?= $rank ?>, <?= $rr ?> LP) </title>
                    <div class="pos">
                        <div id="container">
                            <img src="<?= $rank_img ?>" alt="Rank: <?= $rank ?>">
                            <span class="badge"><?= $rr ?> LP</span>
                        </div>
                    </div>

                    <!-- import jquery -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
                    <script defer>
                        // auto refresh the div every 5 minutes
                        setInterval(function() {
                            $("#container").load("valorant.php?name=<?= $name ?>&tag=<?= $tag ?>&only_content=true&random="+Math.random());
                        }, 300000);

                    </script>

                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap');
                        div.pos {
                            position: relative;
                            width: fit-content;
                        }
                        #container {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            position: relative;
                        }
                        img {
                            width: 100px;
                            height: 100px;
                            position: relative;
                        }
                        .badge {
                            font-family: 'Archivo Black', sans-serif;
                            background-color: #ff4654bf;
                            color: #182837;
                            padding: 5px;
                            border-radius: 5px;
                            position: absolute;
                            bottom: 0;
                            right: 0;
                        }
                    </style>

                <?php
            
            }
        }
    }
?>

