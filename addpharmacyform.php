<form action="addnewpharmacy.php" method="post" target="addnewpharmacyframe" id="addpharmacyform">
    <b>Add Pharmacy</b>
    <br>
    <label for="pharmacy_name">Pharmacy Name:</label>
    <input type="text" id="pharmacy_name" name="pharmacy_name">
    <fieldset id="pharmacy_address">
        <legend>Address</legend>
        <label for="pharmacy_street">Street:</label>
        <input type="text" id="pharmacy_street" name="pharmacy_street">
        <label for="pharmacy_city">City:</label>
        <input type="text" id="pharmacy_city" name="pharmacy_city">
        <label for="pharmacy_state">State:</label>
        <input type="text" id="pharmacy_state" name="pharmacy_state">
        <label for="pharmacy_zip">ZIP:</label>
        <input type="number" id="pharmacy_zip" name="pharmacy_zip">
    </fieldset>    
    <label for="pharmacy_phone">Phone #:</label>
    <input type="tel" id="pharmacy_phone" name="pharmacy_phone">
    <br>
    <label for="pharmacy_email">Email:</label>
    <input type="email" id="pharmacy_email" name="pharmacy_email">
    <input type="submit" value="Submit">
    <iframe name="addnewpharmacyframe"></iframe>
</form>
