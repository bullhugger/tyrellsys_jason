<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jason's Card</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1>Card</h1>
        <div class="mb-3">
            <label for="no_player" class="form-label">Number of Players:</label>
            <input id="no_player" type="number" class="form-control" />
        </div>
        <div class="mb-3">
            <button id="deal_btn" class="btn btn-sm btn-outline-primary mt2">Deal</button>
        </div>
        <div id="player_container" class="border rounded p-3 text-start bg-light" style="max-height: 300px; overflow-y: auto; font-family: monospace; white-space: pre-wrap;"></div>
    </div>
</body>
<script>
    $(document).ready(function() {

        $('#no_player').on('input', function() {
            const value = $(this).val();
            $('#player-container').text("Players: " + value);
        });

        $('#deal_btn').on('click', function () {
            const value = $('#no_player').val();
            if (!value || value <= 0) {
                $('#player-container').text("Input value does not exist or value is invalid");
                return;
            }
            $.ajax({
                url: '<?= base_url("Home/createPlayers"); ?>',
                method: 'POST',
                data: { players: value },
                success: function (response) {
                    const html = response.replace(/\n/g, "<br>");
                    $('#player_container').html(html);
                },
                error: function (xhr, status, error) {
                    $('#player_container').text("Error: " + error);
                }
            });
        });

    });
</script>
</html>

