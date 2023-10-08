const userFullNameSpan = document.getElementById("userFullNameSpan");
var userId = null;
var userFullName = null;
var userEmail = null;
var userFName = null;
var userLName = null;
var userPhone = null;

async function fetchUserInformation() {
    await fetch('../classes/User.php?f=fetch_user_information')
        .then(response => response.json())
        .then(data => {
            userId = data.userId;
            userFullName = data.firstName + " " + data.lastName;
            userEmail = data.email;
            userFullNameSpan.textContent = userFullName;
            userFName = data.firstName;
            userLName = data.lastName;
            userPhone = data.phone;
        })
        .catch(error => {
            // Handle any errors
            console.error('Error:', error);
        });
}

fetchUserInformation();
document.addEventListener('DOMContentLoaded', function () {
    setInterval(fetchUserInformation, 5000);
});