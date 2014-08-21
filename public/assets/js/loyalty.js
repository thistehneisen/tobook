function onCheckAll(obj) {
  if (obj.checked) {
    $("table#tblDataList").find("input:checkbox").prop("checked", true);
  } else {
    $("table#tblDataList").find("input:checkbox").prop("checked", false);
  }
}
