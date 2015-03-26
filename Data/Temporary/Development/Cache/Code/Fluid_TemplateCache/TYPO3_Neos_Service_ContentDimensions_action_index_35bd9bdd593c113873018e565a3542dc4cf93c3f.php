<?php class FluidCache_TYPO3_Neos_Service_ContentDimensions_action_index_35bd9bdd593c113873018e565a3542dc4cf93c3f extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return NULL;
}
public function hasLayout() {
return FALSE;
}

/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '<html>
	<head>
		<title>Content Dimensions</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		<div>
			<h1>Content Dimensions</h1>
			<ul class="contentdimensions">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments1 = array();
$arguments1['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'contentDimensionsPresets', $renderingContext);
$arguments1['key'] = 'dimensionIdentifier';
$arguments1['as'] = 'contentDimensionPresetConfiguration';
$arguments1['reverse'] = false;
$arguments1['iteration'] = NULL;
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
$output3 = '';

$output3 .= '
				<li class="contentdimension">
					<label class="contentdimension-label">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments4 = array();
$arguments4['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'contentDimensionPresetConfiguration.label', $renderingContext);
$arguments4['keepQuotes'] = false;
$arguments4['encoding'] = 'UTF-8';
$arguments4['doubleEncode'] = true;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
return NULL;
};
$value6 = ($arguments4['value'] !== NULL ? $arguments4['value'] : $renderChildrenClosure5());

$output3 .= !is_string($value6) && !(is_object($value6) && method_exists($value6, '__toString')) ? $value6 : htmlspecialchars($value6, ($arguments4['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments4['encoding'], $arguments4['doubleEncode']);

$output3 .= '</label>
					(<span class="contentdimension-identifier">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments7 = array();
$arguments7['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'dimensionIdentifier', $renderingContext);
$arguments7['keepQuotes'] = false;
$arguments7['encoding'] = 'UTF-8';
$arguments7['doubleEncode'] = true;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
return NULL;
};
$value9 = ($arguments7['value'] !== NULL ? $arguments7['value'] : $renderChildrenClosure8());

$output3 .= !is_string($value9) && !(is_object($value9) && method_exists($value9, '__toString')) ? $value9 : htmlspecialchars($value9, ($arguments7['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments7['encoding'], $arguments7['doubleEncode']);

$output3 .= '</span>)
					<span class="contentdimension-icon">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments10 = array();
$arguments10['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'contentDimensionPresetConfiguration.icon', $renderingContext);
$arguments10['keepQuotes'] = false;
$arguments10['encoding'] = 'UTF-8';
$arguments10['doubleEncode'] = true;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
return NULL;
};
$value12 = ($arguments10['value'] !== NULL ? $arguments10['value'] : $renderChildrenClosure11());

$output3 .= !is_string($value12) && !(is_object($value12) && method_exists($value12, '__toString')) ? $value12 : htmlspecialchars($value12, ($arguments10['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments10['encoding'], $arguments10['doubleEncode']);

$output3 .= '</span>
					<h2>Presets</h2>
					<ol class="contentdimension-presets">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments13 = array();
$arguments13['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'contentDimensionPresetConfiguration.presets', $renderingContext);
$arguments13['key'] = 'presetIdentifier';
$arguments13['as'] = 'preset';
$arguments13['reverse'] = false;
$arguments13['iteration'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
$output15 = '';

$output15 .= '
						<li class="contentdimension-preset';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments16 = array();
// Rendering Boolean node
// Rendering Array
$array17 = array();
$array17['0'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'presetIdentifier', $renderingContext);
// Rendering Array
$array18 = array();
$array18['0'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'contentDimensionPresetConfiguration.defaultPreset', $renderingContext);
$arguments16['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', $array17, $array18);
$arguments16['then'] = ' contentdimension-defaultpreset';
$arguments16['else'] = NULL;
$renderChildrenClosure19 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper20 = $self->getViewHelper('$viewHelper20', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper20->setArguments($arguments16);
$viewHelper20->setRenderingContext($renderingContext);
$viewHelper20->setRenderChildrenClosure($renderChildrenClosure19);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output15 .= $viewHelper20->initializeArgumentsAndRender();

$output15 .= '">
							<label class="contentdimension-preset-label">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments21 = array();
$arguments21['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'preset.label', $renderingContext);
$arguments21['keepQuotes'] = false;
$arguments21['encoding'] = 'UTF-8';
$arguments21['doubleEncode'] = true;
$renderChildrenClosure22 = function() use ($renderingContext, $self) {
return NULL;
};
$value23 = ($arguments21['value'] !== NULL ? $arguments21['value'] : $renderChildrenClosure22());

$output15 .= !is_string($value23) && !(is_object($value23) && method_exists($value23, '__toString')) ? $value23 : htmlspecialchars($value23, ($arguments21['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments21['encoding'], $arguments21['doubleEncode']);

$output15 .= '</label>
							<span class="contentdimension-preset-identifier">';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments24 = array();
$arguments24['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'presetIdentifier', $renderingContext);
$arguments24['keepQuotes'] = false;
$arguments24['encoding'] = 'UTF-8';
$arguments24['doubleEncode'] = true;
$renderChildrenClosure25 = function() use ($renderingContext, $self) {
return NULL;
};
$value26 = ($arguments24['value'] !== NULL ? $arguments24['value'] : $renderChildrenClosure25());

$output15 .= !is_string($value26) && !(is_object($value26) && method_exists($value26, '__toString')) ? $value26 : htmlspecialchars($value26, ($arguments24['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments24['encoding'], $arguments24['doubleEncode']);

$output15 .= '</span>
							<ol class="contentdimension-preset-values">
								';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments27 = array();
$arguments27['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'preset.values', $renderingContext);
$arguments27['as'] = 'value';
$arguments27['key'] = '';
$arguments27['reverse'] = false;
$arguments27['iteration'] = NULL;
$renderChildrenClosure28 = function() use ($renderingContext, $self) {
$output29 = '';

$output29 .= '
									<li>';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$arguments30 = array();
$arguments30['value'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'value', $renderingContext);
$arguments30['keepQuotes'] = false;
$arguments30['encoding'] = 'UTF-8';
$arguments30['doubleEncode'] = true;
$renderChildrenClosure31 = function() use ($renderingContext, $self) {
return NULL;
};
$value32 = ($arguments30['value'] !== NULL ? $arguments30['value'] : $renderChildrenClosure31());

$output29 .= !is_string($value32) && !(is_object($value32) && method_exists($value32, '__toString')) ? $value32 : htmlspecialchars($value32, ($arguments30['keepQuotes'] ? ENT_NOQUOTES : ENT_COMPAT), $arguments30['encoding'], $arguments30['doubleEncode']);

$output29 .= '</li>
								';
return $output29;
};

$output15 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments27, $renderChildrenClosure28, $renderingContext);

$output15 .= '
							</ol>
						</li>';
return $output15;
};

$output3 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments13, $renderChildrenClosure14, $renderingContext);

$output3 .= '
					</ol>
				</li>';
return $output3;
};

$output0 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments1, $renderChildrenClosure2, $renderingContext);

$output0 .= '
			</ul>
		</div>
	</body>
</html>';

return $output0;
}


}
#0             10036     