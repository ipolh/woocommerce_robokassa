<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.6.0/clipboard.min.js"></script>
<script>new Clipboard('.btn-clipboard');</script>

<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Roboto:300,400,700');
	#wpcontent{padding-left: 0;}
	.content_holder{
		padding-left: 20px;
		height: 100%;
	}
	.content_holder .big_title_rb{font-size: 36px; margin:0; padding: 1em 0; font-family: inherit; font-weight: 300}
	.content_holder .mid_title_rb{font-size: 24px; margin:0; padding: 15px 0 5px 0; font-family: inherit; font-weight: 300}
	.content_holder .sml_title_rb{font-size: 18px; margin:0; font-family: inherit; font-weight: 300}
	span.code_tregor{
		background-color: #555555;
		border: 1px solid #878787;
		border-radius: 4px;
		color: yellow;
		padding: 1px 5px;
	}
	.menu_rb ul {
	    list-style-type: none;
	    margin: 0;
	    padding: 0;
	    overflow: hidden;
	    background-color: #333;
	}
	.menu_rb li {
	    float: left;
	    margin: 0;
	}
	.menu_rb li a {
	    display: block;
	    color: white;
	    text-align: center;
	    padding: 14px 16px;
	    text-decoration: none;
	}
	.menu_rb li a:hover {
		background-color: #191E23;
		color: #00b9eb;
	}
	.menu_rb .active {
	    background-color: #0073AA;
	    color: #ffffff
	}
</style>

<div class="menu_rb" align="center" style="margin-top: 50px;">
    <h1>Настройки плагина Робокасса для WooCommerce</h1>

    <br>

    <ul>
        <li>
            <a href="?page=main_rb" <?php echo ($_GET['li'] == 'main') ? 'class="active"' : ''; ?>>Основные настройки</a>
        </li>
        <li>
            <a href="?page=sms_rb" <?php echo ($_GET['li'] == 'sms') ? 'class="active"' : ''; ?>>Настройки оповещений</a>
        </li>
        <li>
            <a href="?page=robomarket_rb" <?php echo ($_GET['li'] == 'robomarket') ? 'class="active"' : ''; ?>>Настройки РобоМаркет</a>
        </li>
    </ul>
</div>