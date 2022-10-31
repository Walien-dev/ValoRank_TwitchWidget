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
				<input type="submit" value="Valider">
			</form>
			<style>
				body {
					display: flex;
					justify-content: center;
					align-items: center;
					height: 100vh;
					background-color: #1b1b1b;
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
		echo "<script>var name = '$name'; var tag = '$tag';</script>";
		?>
			<title>ValoRank | <?= $name ?>#<?= $tag ?></title>
			<div class="pos">
				<div id="container">
					<img src="?" alt="Rank: ?">
					<span class="badge">? LP</span>
				</div>
			</div>
			<!-- import jquery -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			<script>
				function getRank() {
					let url = `https://api.henrikdev.xyz/valorant/v1/mmr/eu/${name}/${tag}`;
					// with ajax get the url and get the data
					$.ajax({
						url: url,
						type: "GET",
						success: function(data) {
							let rank = data.data.currenttierpatched;
							let rr = data.data.ranking_in_tier;
							let rank_img = data.data.images.large;
							// change the rank image and the rr
							$("#container img").attr("src", rank_img);
							$("#container img").attr("alt", `Rank: ${rank}`);
							$("#container .badge").text(rr + " LP");
							console.log(`Rank updated, new rank: ${rank}, new rr: ${rr}\n${new Date().getHours()}:${new Date().getMinutes()}:${new Date().getSeconds()}`);
						},
						error: function() {
							$("#container img").attr("src", "./invalid.png");
							$("#container img").attr("alt", "Rank: Unranked");
							$("#container .badge").text("Invalide");
							console.log("Error, the url is not valid or return an error 404");
						}
					});
				}

				getRank();

				// update the rank every 5 minutes
				setInterval(getRank, 300000);
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
?>

