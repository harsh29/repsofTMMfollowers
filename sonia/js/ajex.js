function createXMLHttpRequest() {
  var XMLHttpReq;
  try {
      XMLHttpReq = new ActiveXObject("Msxml2.XMLHTTP");
  }
  catch (e) {
      try {
          XMLHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {
          try {
            XMLHttpReq = new XMLHttpRequest();
          }
          catch (e) {
            return null;
          }
      }
  }
  return XMLHttpReq;
}

function sendRequestByObject(obj, url, proc) {
    obj.open("GET", url, true);
    obj.onreadystatechange = proc;
    obj.send(null);
}