// In: templates/scholarships.twig

$('.js-scholarship-name').click(function(event) {
  event.preventDefault();
  designation = $(event.target).text();

  // Set the 'Designation' in template_variables
  template_variables = jQuery.parseJSON($('#growdough_template_variables').val());
  template_variables['designation'] = designation;
  $('#growdough_template_variables').val(JSON.stringify(template_variables));

  // Set the title and 'Designation' donation_item attribute in the first donation_item
  donation_items = jQuery.parseJSON($('#growdough_donation_items').val());
  donation_item = donation_items[0];
  donation_item['title'] = donation_item['attributes']['Name'] + ' - ' + designation;
  donation_item['attributes']['Designation'] = designation;
  donation_items[0] = donation_item;
  $('#growdough_donation_items').val(JSON.stringify(donation_items));
  $('#growdough-form').submit();
});
