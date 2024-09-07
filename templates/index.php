<?php
if (!defined('ABSPATH')) { return; }

// Define an array of countries
$countries = array(
    'AU' => 'Australia',
    'BD' => 'Bangladesh',
    'BR' => 'Brazil',
    'CN' => 'China',
    'DE' => 'Germany',
    'FR' => 'France',
    'GB' => 'United Kingdom',
    'IN' => 'India',
    'IS' => 'Iceland',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'JP' => 'Japan',
    'LK' => 'Sri Lanka',
    'MX' => 'Mexico',
    'MY' => 'Malaysia',
    'NL' => 'Netherlands',
    'NP' => 'Nepal',
    'PK' => 'Pakistan',
    'RU' => 'Russia',
    'SA' => 'Saudi Arabia',
    'SE' => 'Sweden',
    'SG' => 'Singapore',
    'TH' => 'Thailand',
    'UA' => 'Ukraine',
    'US' => 'United States',
    'AE' => 'United Arab Emirates',
    'VI' => 'United States Virgin Islands',
    'ZA' => 'South Africa',
);

if (isset($_POST['submission'])) {
    global $wpdb;

    $selected_country = isset($_POST['selected_country']) ? sanitize_text_field($_POST['selected_country']) : '';
    $selected_page = isset($_POST['selected_page']) ? sanitize_text_field($_POST['selected_page']) : '';
    $redirect_link = isset($_POST['input_value']) ? sanitize_text_field($_POST['input_value']) : '';

    $country_name = isset($countries[$selected_country]) ? $countries[$selected_country] : '';

    if (!empty($selected_country) && !empty($selected_page) && !empty($redirect_link)) {
        $table_name = $wpdb->prefix . 'ipd_settings';

        $existing_record = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE cc = %s AND pp = %d",
            $selected_country,
            $selected_page
        ));

        if ($existing_record) {
            echo '<div class="notice notice-error is-dismissible"><p>Error: A record with the same country and page already exists.</p></div>';
        } else {
            echo '<script type="text/javascript">
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("page-id").innerText = "' . esc_js($selected_page) . '";
                        document.getElementById("country").innerText = "' . esc_js($country_name) . '";
                        document.getElementById("redirect-url").innerText = "' . esc_js($redirect_link) . '";
                        document.getElementById("page-title").innerText = "' . esc_js(get_the_title($selected_page)). '";
                        var myModal = new bootstrap.Modal(document.getElementById("redirectionModal"));
                        myModal.show();
                    });
                  </script>';

            $data = array(
                'cc' => $selected_country,
                'pp' => $selected_page,
                'readlink' => $redirect_link,
            );

            $result = $wpdb->insert($table_name, $data);

            // Check for errors
            if ($wpdb->last_error) {
                echo '<div class="notice notice-error is-dismissible"><p>Error: ' . esc_html($wpdb->last_error) . '</p></div>';
            } else {
                echo '<div class="notice notice-success is-dismissible"><p>Redirection created successfully!</p></div>';
            }
        }
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Please fill in all fields.</p></div>';
    }
}

// Fetch all pages
$pages = get_pages();

// Default values nothing
$selected_page = isset($_POST['selected_page']) ? sanitize_text_field($_POST['selected_page']) : '';
$selected_country = isset($_POST['selected_country']) ? sanitize_text_field($_POST['selected_country']) : '';
$input_value = isset($_POST['input_value']) ? sanitize_text_field($_POST['input_value']) : '';

?>
<div class="wrap">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-6T/zERAmAE71XBmyNj/fg6xRoHJ1PI5Cjjy4lTfM5+S03TkI8Rpxk1EzM0RroNSXqNGK03Osg1Ac9v8wMijJbA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<form method="post" action="">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Select Page</th>
            <td>
                <select name="selected_page">
                    <option value="">Select a page</option>
                    <?php foreach ($pages as $page) : ?>
                        <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($selected_page, $page->ID); ?>>
                            <?php echo esc_html($page->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Select Country</th>
            <td>
                <select name="selected_country">
                    <option value="">Select a country</option>
                    <?php foreach ($countries as $code => $name) : ?>
                        <option value="<?php echo esc_attr($code); ?>" <?php selected($selected_country, $code); ?>>
                            <?php echo esc_html($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Where to Redirect</th>
            <td>
                <textarea name="input_value" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo esc_textarea($input_value); ?></textarea>
            </td>
        </tr>
    </table>

    <button name="submission" class="button-19" role="button">
      <span class="text">Create Redirection</span>
    </button>

    <button name="reset" class="button-18 reset" role="button">
      <span class="text">Cancel</span>
    </button>

</form>
</div>

<?php
function get_ipd() {
    // Enqueuing the CDN for jQuery Data-Table
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css');
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js', array('jquery'), null, true);
    wp_enqueue_style('my-plugin-style', INC_URL . 'assets/css/style.css', array(), '1.0', 'all');
    wp_enqueue_script('my-plugin-script', INC_URL . 'assets/js/script.js', array('jquery'), '1.0', true);

    // Getting the records for the table  
    global $wpdb;
    $table_name = $wpdb->prefix . 'ipd_settings';

    $ipd = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    return $ipd;
}

$ipd = get_ipd();
global $wpdb;
$table_name = $wpdb->prefix . 'ipd_settings';

// Handle delete request
if (isset($_POST['delete_record']) && isset($_POST['delete_id'])) {

    // Sanitize and validate the ID
    $delete_id = intval($_POST['delete_id']);

    $result = $wpdb->delete($table_name, array('id' => $delete_id));

    // Check for errors
    if ($result === false) {
        echo '<div class="notice notice-error is-dismissible"><p>Error: ' . esc_html($wpdb->last_error) . '</p></div>';
    } else {
        echo '<div class="notice notice-success is-dismissible"><p>Record deleted successfully!</p></div>';
    }

    // Refresh the page to show updated records
    $ipd = get_ipd();
}
?>

<table id="ipd-table" class="display">
    <thead>
        <tr>
            <th>No.</th>
            <th>Country</th>
            <th>Page Title</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Initialize row counter
        $row_number = 1;

        foreach ($ipd as $records) : ?>
            <tr>
                <td><?php echo esc_html($row_number); ?></td>
                <td><?php echo esc_html($countries[$records['cc']]); ?></td>
                <td><?php echo esc_html(get_the_title($records['pp'])); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="delete_id" value="<?php echo esc_attr($records['id']); ?>">
                        <input type="submit" name="delete_record" class="button button-danger" value="Delete">
                    </form>
                </td>
            </tr>
        <?php
        $row_number++;
        endforeach; ?>
    </tbody>
</table>

<script>
jQuery(document).ready(function($) {
    $('#ipd-table').DataTable();
});
</script>

<!-- Modal -->

<div class="modal fade" id="redirectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <img class="alert-img" src="<?php echo INC_URL; ?>/assets/images/Emoji-Alert-500x282.png" width="50px" height="auto" alt="Alert">
        <h5 class="modal-title" id="exampleModalLabel">Important Message</h5>
        </button>
      </div>
      <div class="modal-body">
      <b>Alert !</b> You are creating a redirection of page ID :<b><span id="page-id"></span></b>
      When the Visitor will visit <b><span id="page-title"></span></b> page from <b><span id="country"></span></b> then they will get redirect to <b><span id="redirect-url"></span></b> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK,got it.</button>
      </div>
    </div>
  </div>
</div>
