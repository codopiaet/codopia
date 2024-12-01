$(document).ready(function () {
    // Hover functionality
    $(".star").hover(function () {
        const value = parseInt($(this).attr('data-value'));
        $(".star").each(function (index) {
            if (index < value) {
                $(this).css('color', 'gold');
            } else {
                $(this).css('color', 'black');
            }
        });
    }, function () {
        $(".star").css('color', 'black'); // Return all stars to default black color on hover off
    });

    // Click functionality
    $(".star").click(function () {
        const value = parseInt($(this).attr('data-value'));
        // Set the hidden input value
        $("#starValue").val(value);
        // Apply style to all stars based on the selected value
        $(".star").each(function (index) {
            if (index < value) {
                $(this).css('color', 'gold');
            } else {
                $(this).css('color', 'black');
            }
        });
    });

    // Restore hover effect when hovering again after clicking
    $(".star").on('mouseenter', function () {
        const value = parseInt($(this).attr('data-value'));
        $(".star").each(function (index) {
            if (index < value) {
                $(this).css('color', 'gold');
            } else {
                $(this).css('color', 'black');
            }
        });
    });

    // Submit button functionality (optional)
    $("#out-user").click(function () {
        const rating = $("#starValue").val();
        // Perform submission logic here
    });
});
