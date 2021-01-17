function loadButtons() {
  var btn = $("button#btn")
  var btn_options = btn.button('option');

  var btn2 = btn
    .clone()
    .attr("id", "btn2")
    .button(btn_options)
}