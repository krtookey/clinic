<form action="addnewpharmacy.php" method="post" target="addnewpharmacyframe" id="addpharmacyform">
    <b>Add Pharmacy</b>
    <br>
    <label for="pharmacy_name">Pharmacy Name:</label>
    <input type="text" id="pharmacy_name" name="pharmacy_name" required>
    <fieldset id="pharmacy_address">
        <legend>Address</legend>
        <div class="rowOfInputs pharmacyInput">
            <label for="pharmacy_street">Street:</label>
            <input type="text" id="pharmacy_street" name="pharmacy_street" required>
            <label for="pharmacy_city">City:</label>
            <input type="text" id="pharmacy_city" name="pharmacy_city" required>
        </div>
        <div class="rowOfInputs pharmacyInput">
            <label for="pharmacy_state">State:</label>
            <input type="text" id="pharmacy_state" name="pharmacy_state" required>
            <label for="pharmacy_zip">ZIP:</label>
            <input type="number" id="pharmacy_zip" name="pharmacy_zip" required>
        </div>
    </fieldset>
    <div class="rowOfInputs pharmacyInput">
        <label for="pharmacy_phone">Phone #:</label>
        <input type="tel" id="pharmacy_phone" name="pharmacy_phone" required>
        <label for="pharmacy_email">Email:</label>
        <input type="email" id="pharmacy_email" name="pharmacy_email">
        <input type="submit" value="Submit"">
    </div>    
    <iframe name="addnewpharmacyframe" class="results_iframe"></iframe>
</form>
