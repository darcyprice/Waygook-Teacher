function displayEdit(isStudent) {
  if (isStudent) {
    document.querySelector("#teacher-edit").style.display = "none";
  }
};

function displayOldPassword() {
  document.querySelector("#edit-profile-old-pw").style.display = "block";
}