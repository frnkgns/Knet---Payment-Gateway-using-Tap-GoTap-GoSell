### How it Works

* The PHP script calls Tap Payments' `/v2/authorize/` API.
* Tap returns a KNET payment URL.
* The script redirects the user's browser to that KNET URL.
* After payment, Tap notifies the `post-handler.php` (server-to-server) and redirects the user to the `redirect-handler.php`.

### Running the Example

1.  Access `https://thedomain.com/gotap/tap.php` in the browser.
2.  You should be redirected to the KNET payment page.

---

### Important: KNET Access Outside Kuwait

If you are not in Kuwait (e.g., in Cagayan Valley, Philippines), you will likely get a "connection failure" error when the browser tries to load the KNET payment page.

**Solution:** **Use a VPN.** Connect the computer to a VPN server located in **Kuwait** (or another Middle Eastern country) before accessing the script.

---

### Troubleshooting

* **Blank Page:** Add `ini_set('display_errors', 1); error_reporting(E_ALL);` to the top of `tap.php` for PHP errors.
* **"upstream connect error..." / "connection failure":** This means the browser/network cannot reach the KNET payment page. See the VPN note above.
* **Other Errors:** Check the web server's and PHP's error logs for more details.
