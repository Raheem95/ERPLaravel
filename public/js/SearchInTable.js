var labels = document.querySelectorAll(".truncate-label");

// Attach event listeners to handle hover events
labels.forEach(function (label) {
  label.addEventListener("mouseenter", function () {
    var popup = this.nextElementSibling;
    popup.style.display = "block";
  });

  label.addEventListener("mouseleave", function () {
    var popup = this.nextElementSibling;
    popup.style.display = "none";
  });
});

$(document).ready(function () {
  $(".Search").on("keyup", function () {
    var searchText = $(this).val().toLowerCase();
    var TableID = $(this).attr("id");
    var Counter = 1;
    $("#" + TableID + "Table tbody tr").each(function () {
      var rowData = $(this).find("td:first").text().toLowerCase(); // Get text of the first td only
      if (rowData.indexOf(searchText) === -1) {
        $(this).hide();
      } else {
        if (Counter <= 5) {
          $(this).show();
        } else {
          $(this).hide();
        }
        Counter++;
      }
    });
  });
});
