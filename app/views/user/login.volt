<h1 class="head"><span class="glyphicon glyphicon-user"></span> Restricted area</h1>
<form method="post">
    <ul>
        <li><label></label></li>

        <li>
            <label for="email">Email</label>
            <input type='text' id="email" name="auth[email]" />
        </li>
        <li>
            <label for="pass">Password</label>
            <input type='password' id="pass" name="auth[pass]"/>
        </li>
        <li>
            <input type='checkbox' id="cookie" value="1" name="auth[cookie]" class="default"/>
            <label for="cookie" class="default">Remember me</label>
        </li>
        <li>
            <br/>
            <label></label>
            <button class="btn btn-success"><span class="glyphicon glyphicon-user"></span> Sign in</button>
        </li>

    </ul>
</form>
