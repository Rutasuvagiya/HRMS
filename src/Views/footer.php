</div>
</div>

<script>
$(document).ready(function () {
    function fetchNotifications() {
        $.ajax({
            url: "getNotifications",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let count = data.length;
                $("#notification-count").text(count);
                
                let listHtml = "";
                data.forEach(notification => {
                    listHtml += `<li>${notification}</li>`;
                });
                $("#notification-list").html(listHtml);
            }
        });
    }

    // Fetch notifications every 5 seconds
    setInterval(fetchNotifications, 5000);

    // Toggle notification dropdown
    $("#notification-btn").click(function () {
        $("#notification-dropdown").toggle();
    });

    // Fetch notifications on page load
    fetchNotifications();

    
});

</script>
</body>
</html>
