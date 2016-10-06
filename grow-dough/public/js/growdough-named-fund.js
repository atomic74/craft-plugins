// In: templates/giveToANamedFund.twig

$('#growdough-form').submit(function(event) {
  designation = $('#growdough-form input[name=designation]').val();
  if (designation.length>0) {
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
  }
  else {
    event.preventDefault();
    alert('Please Fill out the Donation Form before submitting.')
  }
});
