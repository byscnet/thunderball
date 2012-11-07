<?php
class Zend_View_Helper_LayoutHelper extends Zend_View_Helper_Abstract
{
	public function layoutHelper()
	{
		return $this;
	}

	public function getHeadline($headlineText, $buttonList)
	{
		$headline = array();
		$headline[] = '<div style="border-bottom: 2px solid #eee; background-color: #fff; width: 985px; padding-bottom: 15px; margin-bottom: 25px;">';
		$headline[] = '<span id="headline" style="font-family: Verdana,Arial,sans-serif; font-size: 14px; font-weight: bold;">' . $headlineText . '</span>';
		$headline[] = '<div style="float: right">';
		$buttonCount = 0;
		$style = array();
		foreach ($buttonList as $id => $value) {
			$style[] = 'float: left';
			if ($buttonCount > 0) {
				$style[] = 'padding-left: 15px';
			}
			$headline[] = '<div style="' . join('; ', $style) . '"><button id="' . $id . '">' . $value . '</button></div>';
			$buttonCount++;
		}
		$headline[] = '';
		$headline[] = '</div>';
		$headline[] = '</div>';
		return join('', $headline);
	}

	public function getDeleteButton($parameters)
	{
		return $this->getButton($parameters, 'deleteItem', 'btnDelete', 'Löschen');
	}

	public function getCartButton($parameters)
	{
		return $this->getButton($parameters, 'cartItem', 'btnCart', 'Warenkorb');
	}

	public function getEditButton($parameters)
	{
		return $this->getButton($parameters, 'editItem', 'btnEdit', 'Bearbeiten');
	}

	public function getWrenchButton($parameters)
	{
		return $this->getButton($parameters, 'wrenchItem', 'btnWrench', 'Konfigurieren');
	}

	public function getCalculatorButton($parameters)
	{
		return $this->getButton($parameters, 'calculatorItem', 'btnCalculator', 'Preistabelle');
	}

	public function getLockButton($parameters)
	{
		return $this->getButton($parameters, 'unlockItem', 'btnLock', 'Entsperren');
	}

	public function getUnlockButton($parameters)
	{
		return $this->getButton($parameters, 'lockItem', 'btnUnlock', 'Sperren');
	}

	public function getDetailButton($parameters)
	{
		return $this->getButton($parameters, 'showItem', 'btnDetail', 'Detail');
	}

	public function getPlayButton($parameters)
	{
		return $this->getButton($parameters, 'playItem', 'btnPlay', 'Starten');
	}

	public function getSaveButton($parameters)
	{
		return $this->getButton($parameters, 'saveItem', 'btnSave', 'Speichern');
	}

	public function getCancelButton($parameters)
	{
		return $this->getButton($parameters, 'cancelItem', 'btnCancel', 'Abbrechen');
	}
	
	public function getNotOptionalInfo()
	{
		return '<div style="float: right;">* Pflichtfelder</div>';
	}

	public function getButton($parameters, $jsFuncName, $cssClass, $label)
	{
		$func = $jsFuncName . '(';
		if (is_array($parameters)) {
			$p = join(',', $parameters);
		} else {
			$p = $parameters;
		}
		$func .= $p . '); return false;';
		$html = '<button class="' . $cssClass . '" onclick="' . $func . '" data-rel="' . $p . '">' . $label . '</button>';
		return $html;
	}
}