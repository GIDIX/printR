<?php
	/**
	 *	@author 	gidix.de (Benjamin Schmidt)
	 * 	@since 		08.06.2013 18:51
	 *	@version 	0.1
	 *	@copyright	See README.
	 */

	class printR {
		private static $recursiveAllowed = false;
		private static $disabled = false;
		private static $filter = array(
			'(',
			')',
			' '
		);

		public static $parseJSON = true;

		public static function disable() {
			self::$disabled = true;
		}

		public static function enable() {
			self::$disabled = false;
		}

		public static function styles() {
			return '
				<style type="text/css">
					.printR {
						padding: 10px;
						border: 1px solid #e0e0e0;
						background: #fff;
						margin-top: 5px;
						margin-bottom: 5px;
					}

						.printR_double, .printR_float, .printR_integer {
							border: 1px solid #B9BD9D;
						}

						.printR_array {
							border: 1px solid #9DB4BD;
						}

						.printR_object {
							border: 1px solid #A99DBD;
						}

						.printR_boolean {
							border: 1px solid #BD9DAB;
						}

						.printR_string {
							border: 1px solid #BDB59D;
						}

						.printR_type {
							background: #eee;
							margin: -10px;
							margin-bottom: 10px;
							padding: 10px;
							border-bottom: 1px solid #e0e0e0;
							text-transform: capitalize;
							color: rgba(0, 0, 0, 0.8);
						}

							.printR_type.printR_only {
								margin-bottom: -10px;
								border-bottom: 0px;
							}

							.printR_array .printR_type {
								background: #B8D3DE;
								border-bottom: 1px solid #9DB4BD;
							}

							.printR_double .printR_type, .printR_float .printR_type, .printR_integer .printR_type {
								background: #DADEB8;
								border-bottom: 1px solid #B9BD9D;
							}

							.printR_object .printR_type {
								background: #C7B8DE;
								border-bottom: 1px solid #A99DBD;
							}

							.printR_boolean .printR_type {
								background: #DEB8C9;
								border-bottom: 1px solid #BD9DAB;
							}

							.printR_string .printR_type {
								background: #DED5B8;
								border-bottom: 1px solid #BDB59D;
							}

							.printR_type h3 {
								padding: 0;
								margin: 0;
								font-weight: 300;
								font-size: 18px;
							}

							.printR_type .printR_count {
								font-size: 12px;
								font-weight: 500;
								text-transform: uppercase;
								color: rgba(0, 0, 0, 0.5);
							}

						.printR ul {
							list-style-type: none;
							padding: 0;
							margin: 0;
						}

							.printR ul li {
								padding: 7.5px;
								border-bottom: 1px solid #eee;
							}

							.printR ul li:last-child {
								border-bottom: 0;
							}

							.printR ul ul {
								margin-top: 7.5px;
								margin-bottom: -7.5px;
								border-top: 1px solid #eee;
							}

								.printR ul ul li {
									border-left: 2px solid #9DB4BD;
									padding-left: 15px;
								}

						.printR_key {
							display: inline-block;
							padding-right: 20px;
							font-weight: bold;
						}

						.printR_value {
							display: inline-block;
							font-style: italic;
						}

							.printR_valueType {
								font-style: normal;
								color: #9b9b9b;
							}

						.printR_value.printR_special {
							font-style: normal;
						}

						.printR_nonItalic {
							font-style: normal !important;
						}
				</style>
			';
		}

		private static function showArray($var, $recursive, $wasJSON) {
			$backtrace = debug_backtrace();

			if (!$recursive)
				echo '
					<div class="printR_type">
						<h3>'.($wasJSON ? 'JSON' : 'Array').' <span class="printR_count">('.count($var).' elements, line '.$backtrace[1]['line'].')</span></h3>
					</div>
				';

			echo '<ul>';

			foreach ($var as $k => $v) {
				echo '
					<li>
						<div class="printR_key">'.(self::is_assoc($var) ? $k : '['.$k.']').'</div>
				';

				if (is_array($v)) {
					echo '<div class="printR_value printR_special">Array</div>';

					self::show($v, true);
				} else {
					echo '<div class="printR_value"><small class="printR_valueType">('.gettype($v).')</small> ' . $v . '</div>';
				}

				echo '
					</li>
				';
			}

			echo '
				</ul>
			';
		}

		private static function showObject($var) {
			$backtrace = debug_backtrace();

			echo '
				<div class="printR_type">
					<h3>Object <span class="printR_count">(line '.$backtrace[1]['line'].')</span></h3>
				</div>

				<ul>
					<li>
						<div class="printR_key">
							[Class]
						</div>

						<div class="printR_value">
							'.get_class($var).'
						</div>
					</li>

					<li>
						<div class="printR_key">
							[Vars]
						</div>

						<div class="printR_value printR_special">
							Array
						</div>
			';

			self::show(get_object_vars($var), true);

			echo '
					</li>

					<li>
						<div class="printR_key">
							[Methods]
						</div>

						<div class="printR_value printR_special">
							Array
						</div>
			';

			self::show(get_class_methods(get_class($var)), true);

			echo '
					</li>
				</ul>
			';
		}

		private static function showNull() {
			$backtrace = debug_backtrace();

			echo '
				<div class="printR_type printR_only">
					<h3>NULL <span class="printR_count">(line '.$backtrace[1]['line'].')</span></h3>
				</div>
			';
		}

		private static function showElse($var) {
			$backtrace = debug_backtrace();

			echo '
				<div class="printR_type">
					<h3>'.gettype($var).' <span class="printR_count">(line '.$backtrace[1]['line'].')</span></h3>
				</div>

				<ul>
					<li>
						<div class="printR_value printR_nonItalic">
							'.(is_bool($var) ? ($var) ? 'true' : 'false' : htmlspecialchars($var)).'
						</div>
					</li>
				</ul>
			';
		}

		public static function show($var, $recursive = false) {
			if (self::$disabled) return;

			if ($recursive && !self::$recursiveAllowed) {
				throw new Exception('Initial recursive call is not allowed.');
			}

			self::$recursiveAllowed = true;
			$wasJSON = false;

			if (self::$parseJSON && is_string($var)) {
				$json = json_decode($var, true);

				if ($json !== NULL && $json !== FALSE) {
					$var = $json;
					$wasJSON = true;
				}
			}

			if (!$recursive) {
				echo '
					<div class="printR printR_'.($wasJSON ? 'array' : gettype($var)).'">
				';
			}

			if (is_array($var)) {
				self::showArray($var, $recursive, $wasJSON);
			} else if (is_numeric($var) || is_string($var) || is_bool($var)) {
				self::showElse($var);
			} else if (is_object($var)) {
				self::showObject($var);
			} else if (is_null($var)) {
				self::showNull();
			}

			if (!$recursive) {
				echo '</div>';
				self::$recursiveAllowed = false;
			}
		}

		public static function is_assoc($arr) {
			return array_keys($arr) !== range(0, count($arr) - 1);
		}
	}
?>