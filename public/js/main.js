function customConfirm(message, callback) {
  var confirmContainer = document.getElementById("customConfirmContainer");
  var confirmDiv = document.createElement("div");
  confirmDiv.classList.add("custom-confirm");
  var confirmMessage = document.createElement("p");
  confirmMessage.textContent = message;
  confirmDiv.appendChild(confirmMessage);

  var btnConfirm = document.createElement("button");
  btnConfirm.textContent = "تأكيد";
  btnConfirm.classList.add("btn", "btn-confirm");
  btnConfirm.onclick = function () {
    confirmContainer.removeChild(confirmDiv);
    callback(true);
  };
  confirmDiv.appendChild(btnConfirm);

  var btnCancel = document.createElement("button");
  btnCancel.textContent = "إلغاء";
  btnCancel.classList.add("btn", "btn-cancel");
  btnCancel.onclick = function () {
    confirmContainer.removeChild(confirmDiv);
    callback(false);
  };
  confirmDiv.appendChild(btnCancel);

  confirmContainer.appendChild(confirmDiv);
}

function customAlert(message, type) {
  var alertContainer = $("#customAlertContainer"); // Using jQuery to select the container

  var alertDiv = $('<div class="custom-alert"></div>'); // Creating a jQuery object for the alert

  switch (type) {
    case "danger":
      alertDiv.addClass("custom-alert-danger");
      break;
    case "success":
      alertDiv.addClass("custom-alert-success");
      break;
    case "info":
      alertDiv.addClass("custom-alert-info");
      break;
    default:
      break;
  }

  alertDiv.text(message); // Setting text content using jQuery

  alertContainer.append(alertDiv); // Appending the alert to the container

  // Automatically remove the alert after 5 seconds
  setTimeout(function () {
    alertDiv.animate(
      {
        opacity: 0,
      },
      "slow",
      function () {
        $(this).remove(); // Removing the alert from DOM after animation
      }
    );
  }, 3000); // 5000 milliseconds (5 seconds) timeout
}

function confirmDelete(Message, FormID) {
  customConfirm(Message, function (result) {
    if (result) {
      // If confirmed, submit the corresponding form
      document.getElementById(FormID).submit();
    } else {
      // Otherwise, do nothing (cancel deletion)
      customAlert("تم إلغاء العملية", "info");
    }
  });
}

// main menu scripts
document.addEventListener("DOMContentLoaded", function () {
  var label = document.querySelector("h1");
  if (label) {
    document.title = label.textContent || label.innerText;
  }
});

function handleClickOutside(event) {
  var div = document.getElementById("ContainerNav");
  var sidebar = document.getElementById("sidebar");
  var icon = document.getElementById("icon");
  var arrowIcon = document.getElementById("ArrorIcon");
  // Check if the click is outside the sidebar and not on the arrow icon
  if (!div.contains(event.target) && event.target.id != "ArrorIcon") {
    sidebar.style.right = "-200px";
    icon.style.right = "25px";
    $("#icon")
      .empty()
      .append(
        $('<i id="ArrorIcon" class="fa-solid fa-circle-arrow-left"></i>')
      );
  }
}
// Add event listener to the document to detect clicks
document.addEventListener("click", handleClickOutside);

function toggleSidebar(Type) {
  var sidebar = document.getElementById("sidebar");
  var icon = document.getElementById("icon");
  if (sidebar.style.right === "0px") {
    if (Type != 1) {
      sidebar.style.right = "-200px";
      icon.style.right = "25px";
      $("#icon")
        .empty()
        .append(
          $('<i id="ArrorIcon"  class="fa-solid fa-circle-arrow-left "></i>')
        );
    }
  } else {
    sidebar.style.right = "0px";
    icon.style.right = "225px";
    $("#icon")
      .empty()
      .append($('<i id="ArrorIcon" class="fa-solid fa-circle-xmark"></i>'));
  }
}
$(document).on("click", ".ViewSubMenu", function () {
  if ($("#" + $(this).attr("id") + "Div").css("display") == "grid")
    $("#" + $(this).attr("id") + "Div").css("display", "none");
  else $("#" + $(this).attr("id") + "Div").css("display", "grid");
});

document.addEventListener("DOMContentLoaded", function () {
  var message = $("#ResultText").val();
  var type = $("#ResultType").val();
  customAlert(message, type);
});
