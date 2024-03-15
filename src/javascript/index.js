function showSection(sectionID) {
  if (document.getElementById(sectionID).style.display == "block") {
    document.getElementById(sectionID).style.display = "none";
  } else {
    document.getElementById(sectionID).style.display = "block";
  }
}