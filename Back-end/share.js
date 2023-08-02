function copyToClipboard() {
    var linkInput = document.getElementById("linkInput");
    var linkText = linkInput.value;
  
    navigator.clipboard.writeText(linkText)
      .then(function() {
        var copyButton = document.getElementById("copy");
        copyButton.innerText = "Link Copied!";
      })
      .catch(function(error) {
        console.error("Failed to copy link: ", error);
      });
  }
  