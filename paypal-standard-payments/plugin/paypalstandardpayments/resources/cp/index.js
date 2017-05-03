$('#handleSelect').change(function() {
  $('#getHandles').submit();
});
$('a.stale').click(function() {
  return confirm('{{ staleOrdersCount }} orders older than 3 months will be deleted. Do you want to proceed?');
});
$('a.submit').click(function() {
  return confirm('ALL {{ orders|length }} orders will be deleted. Do you want to proceed?');
});
$('a.delete').click(function() {
  return confirm('Are you sure you want to delete this order?');
});
