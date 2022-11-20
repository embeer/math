# math

A few classes (for now actually only one) serving maths exercising.

### v. 1.0.0
released on 20th March 2020

### Usage

#### Class `Expressions`

Execute the below code to obtain an array of expressions coded for HTML ready to display in browser.
 
```php
$expressions = (new Expressions($parameters))->getExpressions();
```

Example result

``` html

1<sup>4</sup>&frasl;<sub>6</sub> + (<sup>4</sup>&frasl;<sub>8</sub> + 2.1 + 6 + <sup>5</sup>&frasl;<sub>10</sub>) * <sup>3</sup>&frasl;<sub>4</sub> =
<sup>5</sup>&frasl;<sub>10</sub> * <sup>5</sup>&frasl;<sub>5</sub> * (2<sup>1</sup>&frasl;<sub>3</sub> + <sup>1</sup>&frasl;<sub>6</sub> - 10) - 10 =
5 - 6 * (<sup>4</sup>&frasl;<sub>8</sub> - 6) + 4 =
<sup>6</sup>&frasl;<sub>4</sub> - (6 : (-<sup>6</sup>&frasl;<sub>9</sub>) - 2) =

```


giving the following display


1<sup>4</sup>&frasl;<sub>6</sub> + (<sup>4</sup>&frasl;<sub>8</sub> + 2.1 + 6 + <sup>5</sup>&frasl;<sub>10</sub>) * <sup>3</sup>&frasl;<sub>4</sub> =<br><br>
<sup>5</sup>&frasl;<sub>10</sub> * <sup>5</sup>&frasl;<sub>5</sub> * (2<sup>1</sup>&frasl;<sub>3</sub> + <sup>1</sup>&frasl;<sub>6</sub> - 10) - 10 =<br><br>
5 - 6 * (<sup>4</sup>&frasl;<sub>8</sub> - 6) + 4 =<br><br>
<sup>6</sup>&frasl;<sub>4</sub> - (6 : (-<sup>6</sup>&frasl;<sub>9</sub>) - 2) =<br><br>


Class internal variables are self documented. The defaults can be changed passing new values to the class constructor as an array of pairs `"variableName" => variableValue`.

Note: `:` (colon) stands for division operator symbol.

