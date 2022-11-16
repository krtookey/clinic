<form action="addnewmedstodb.php" method="post" target="addnewdrugframe" id="adddrugtodatabase">
    <b>Add Drug to Database</b> 
    <br>
    <label for="brandname">Brand Name:</label>
    <input type="text" id="brandname" name="brandname">
    <br>
    <label for="genericname">Generic Name:</label>
    <input type="text" id="genericname" name="genericname" required>
    <br>
    <input type="submit" value="Submit">
    <iframe name="addnewdrugframe" class="results_iframe"></iframe>
</form>