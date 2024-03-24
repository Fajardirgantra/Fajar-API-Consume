<?php
    // Fungsi untuk mendapatkan daftar negara dari API
    function getCountries() {
        $api_key = "0f2a1759b93f2e0a94ffba8eb7b6191f14bd82d1a9c42446ad7b56678668350e";
        $api_url = "https://apiv3.apifootball.com/?action=get_countries&APIkey=$api_key";
        $response = file_get_contents($api_url);
        return json_decode($response, true);
    }

    // Fungsi untuk mendapatkan daftar liga berdasarkan ID negara
    function getLeaguesByCountry($country_id) {
        $api_key = "0f2a1759b93f2e0a94ffba8eb7b6191f14bd82d1a9c42446ad7b56678668350e";
        $api_url = "https://apiv3.apifootball.com/?action=get_leagues&country_id=$country_id&APIkey=$api_key";
        $response = file_get_contents($api_url);
        return json_decode($response, true);
    }
    // Fungsi untik mendapatkan data klassmen berdasrkan leaguge id
    function getStandings($league_id) {
        $api_key = "0f2a1759b93f2e0a94ffba8eb7b6191f14bd82d1a9c42446ad7b56678668350e";
        $api_url = "https://apiv3.apifootball.com/?action=get_standings&league_id=$league_id&APIkey=$api_key";
        $response = file_get_contents($api_url);
        return json_decode($response, true);
        
        
    }
   
    // Memeriksa apakah parameter negara telah dipilih
    $selected_country = isset($_GET['country']) ? $_GET['country'] : null;

    // Jika negara telah dipilih, mendapatkan daftar liga untuk negara tersebut
    $leagues = [];
    if ($selected_country) {
        $leagues = getLeaguesByCountry($selected_country);
    }
    // Memeriksa apakah parameter League telah dipilih
    $selected_league = isset($_GET['league']) ? $_GET['league'] : null;

    // Data standings
    $standings = [];
    if ($selected_league) {
    $standings = getStandings($selected_league);
    
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football League Standings</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Football League Standings</h1>

        <!-- Dropdown untuk memilih negara -->
        <form action="" method="GET" class="mb-3">
            <div class="form-group">
                <label for="countrySelect">Select Country:</label>
                <select class="form-control" id="countrySelect" name="country" onchange="this.form.submit()">
                    <option value="">Choose Country</option>
                    <?php
                        // Mendapatkan daftar negara
                        $countries = getCountries();

                        // Output opsi untuk setiap negara
                        foreach ($countries as $country) {
                            $selected = ($selected_country == $country['country_id']) ? 'selected' : '';
                            echo "<option value='{$country['country_id']}' $selected>{$country['country_name']}</option>";
                        }
                    ?>
                </select>
            </div>
        

        <!-- Dropdown untuk memilih liga -->
        <?php if (!empty($leagues)): ?>
            <div class="form-group">
                <label for="leagueSelect">Select League:</label>
                <select class="form-control" id="leagueSelect" name="league" onchange="this.form.submit()">
                    <option value="">Choose League</option>
            <?php
                // Output opsi untuk setiap liga
                foreach ($leagues as $league) {
                    $selected = ($selected_league == $league['league_id']) ? 'selected' : '';
                    echo "<option value='{$league['league_id']}' $selected>{$league['league_name']}</option>";
                }
            ?>
        </select>
    </div> 
        <?php endif; ?>
        </form>
        <!-- Tabel untuk menampilkan data liga -->
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th scope="col">Position</th>
                    <th scope="col">Club</th>
                    <th scope="col">Matches</th>
                    <th scope="col">Win</th>
                    <th scope="col">Draw</th>
                    <th scope="col">Lose</th>
                    <th scope="col">Point</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($standings) && !isset($standings['error'])): ?>
                <?php foreach ($standings as $team): ?>
                    <tr>
                        <td class="text-center"><?php echo $team['overall_league_position']; ?></td>
                        <td>
                            <img src="<?php echo $team['team_badge']; ?>" alt="Logo team" width="50px">
                            <?php echo $team['team_name']; ?>
                        </td>
                        <td class="text-center"><?php echo $team['overall_league_payed']; ?></td>
                        <td class="text-center"><?php echo $team['overall_league_W']; ?></td>
                        <td class="text-center"><?php echo $team['overall_league_D']; ?></td>
                        <td class="text-center"><?php echo $team['overall_league_L']; ?></td>
                        <td class="text-center"><?php echo $team['overall_league_PTS']; ?></td>
                    </tr>
                <?php endforeach; ?>    
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">No standings available</td>
                </tr>
            <?php endif; ?> 
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
