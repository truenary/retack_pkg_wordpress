<div class="main">
    <div class="header">
        <img src="https://docs.retack.ai/wp-content/uploads/2024/08/retack_logo.png" alt="Retack AI">
    </div>
    <div class="banner">
        <img src="https://docs.retack.ai/wp-content/uploads/2024/08/Frame-1000003629.png" alt="Retack Dashboard">
    </div>
    <div class="input-features">
        <div id="input">
            <div class="input-title-1">
                <p>Input</p>
            </div>
			<div class='border'>
			</div>
            <form method="post" action="options.php">
                <?php
                settings_fields('elp_options_group');
                do_settings_sections('elp_options_group');
                ?>
                <div class="api-form">
                    <label for="elp_api_key" class="api-form-label">Enter Environment Key</label>
                    <div class="api-form-input">
                        <input type="text" id="elp_api_key" name="elp_api_key" class="api-form-input-value" value="<?php echo esc_attr(get_option('elp_api_key')); ?>" />
                        <button type="submit" class="button-primary">
							Save
						</button>
                    </div>
                </div>
            </form>
            <div class="input-title-2">
                <p>Learn More</p>
            </div>
            <ol class="link-list">
                <li><a href="https://example.com/website" target="_blank">Website</a></li>
                <li><a href="https://example.com/docs" target="_blank">Docs</a></li>
                <li><a href="https://example.com/blogs" target="_blank">Blogs</a></li>
                <li>Contact support team: <a href="mailto:contact@truenary.com">contact@truenary.com</a></li>
            </ol>
        </div>
        <div id="features">
            <div class="input-title-1">
                <p>Features</p>
            </div>
			<div class='border'></div>
	            <div class='features-list'>
				<div class="list-div">
					<div class="alert">
						<img src="https://docs.retack.ai/wp-content/uploads/2024/08/alert-2-1.png" alt="Error">
					</div>
					<p>Get Instant Error Alerts</p>
				</div>
                <div class="list-div">
                    <div class="insight">
                        <img src="https://docs.retack.ai/wp-content/uploads/2024/08/search-2-1.png" alt="Insights">
                    </div>
                    <p>Smart Insights</p>
                </div>
                <div class="list-div">
                    <div class="fix">
                        <img src="https://docs.retack.ai/wp-content/uploads/2024/08/process-1.png" alt="Code Fix">
                    </div>
                    <p>Code Fix Automation</p>
                </div>
                <div class="list-div">
                    <div class='plan'>
                        <img src="https://docs.retack.ai/wp-content/uploads/2024/08/gift-1.png" alt="Plan">
                    </div>
                    <p>Generous Free Plan</p>
                </div>
            </div>
            <div class="input-title-3">
                <p>Effortless Integration with</p>
            </div>
            <div class="socials">
				<div class='slack'>
                	<img src="https://docs.retack.ai/wp-content/uploads/2024/08/slack_logo.png" alt="Slack">
				</div>
				<div class='email'>
                	<img src="https://docs.retack.ai/wp-content/uploads/2024/08/email_logo.png" alt="Email">
				</div>
				<div class='teams'>
                	<img src="https://docs.retack.ai/wp-content/uploads/2024/08/teams_logo.png" alt="Microsoft Teams">
				</div>
            </div>
        </div>
    </div>
</div>