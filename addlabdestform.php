<form action="addnewlabdest.php" method="post" target="addnewlabdestframe" id="addlabdestform">
    <b>Add Lab Destination</b>
    <br>
    <label for="labdest_name">Lab Name:</label>
    <input type="text" id="labdest_name" name="labdest_name">
    <fieldset id="labdest_address">
        <legend>Address</legend>
        <label for="labdest_street">Street:</label>
        <input type="text" id="labdest_street" name="labdest_street">
        <label for="labdest_city">City:</label>
        <input type="text" id="labdest_city" name="labdest_city">
        <label for="labdest_state">State:</label>
        <input type="text" id="labdest_state" name="labdest_state">
        <label for="labdest_zip">ZIP:</label>
        <input type="number" id="labdest_zip" name="labdest_zip">
    </fieldset>    
    <label for="labdest_phone">Phone #:</label>
    <input type="tel" id="labdest_phone" name="labdest_phone">
    <br>
    <label for="labdest_email">Email:</label>
    <input type="email" id="labdest_email" name="labdest_email">
    <input type="submit" value="Submit">
    <iframe name="addnewlabdestframe"></iframe>
</form>