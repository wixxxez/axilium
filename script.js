/*==============================================================	PRELOADER    =============================================*/
$(window).on('load', function () {
	var $preloader = $('#preloader'),
	$fountainTextG = $preloader.find('#fountainTextG');
	$fountainTextG.fadeOut();
	$preloader.fadeOut().end().delay(500).fadeOut('slow');
});

/*==========================================================	Випадне меню    ==============================================*/
$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
	if (!$(this).next().hasClass('show')) {
		$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
	}
	let $subMenu = $(this).next(".dropdown-menu");
	$subMenu.toggleClass('show');

	return false;
});

/*========================================================	Для Header  ======================================================*/
function up() {
	let top = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
	if(top > 0) {
		window.scrollBy(0,((top+100)/-10));
		t = setTimeout('up()',20); // "setTimeout" дозволяє викликати функцію один раз через певний інтервал часу.
	} 
	else {
		clearTimeout(t);
	}
	return false;
};


/*======================================================	Скролл до якорів    ==============================================*/
// збираємо всі якорі; встановлюємо час анімації і кількість кадрів
const anchors = [].slice.call(document.querySelectorAll('a[href*="#"]')),
animationTime = 500, // секунди
framesCount = 250; // кадри

anchors.forEach(function(item) {
  	// кожному якорю присвоюємо обробник події
  	item.addEventListener('click', function(e) {
    	// прибираємо стандартну поведінку
    	e.preventDefault();

    	// для кожного якоря беремо відповідний йому елемент і визначаємо його координату Y
    	let coordY = document.querySelector(item.getAttribute('href')).getBoundingClientRect().top + window.pageYOffset;
    																			// "window.pageYOffset" Поточна прокрутка зверху
    											// "getBoundingClientRect" повертає розмір елемента і його позицію щодо viewport
    	// запускаємо інтервал
    	let scroller = setInterval(function() { // "setInterval" дозволяє викликати функцію регулярно, повторюючи виклик 
    											//	через певний інтервал часу.
      		// рахуємо на скільки скролить за 1 такт
      		let scrollBy = coordY / framesCount;

      		// якщо к-ть пікселів для скролла за 1 такт більше відстані до елемента і дно сторінки не досягнуто
      		if(scrollBy > window.pageYOffset - coordY && window.innerHeight + window.pageYOffset < document.body.offsetHeight) {
        											// "window.innerHeight" Вся ширина вікна	
        		// то скролимо на к-ть пікселів, яка відповідає одному такту
        		window.scrollBy(0, scrollBy);
        	} else {
        		// інакше добираємося до елемента і виходимо з інтервалу
        		window.scrollTo(0, coordY);
        		clearInterval(scroller);
        	}
    	// час інтервалу дорівнює частці часу анімації до кількості кадрів
    	}, animationTime / framesCount);
    });
 });

/*=======================================================	Для Логін Форми  =================================================*/
let modal = document.getElementById('id01');

// Коли користувач клацне де-небудь поза модальним вікном, воно закриється
window.onclick = function(event) {
	if (event.target == modal) { // ссилка на об'єкт
		modal.style.display = "none";
	}
};

document.getElementById('sign').onclick = function() {
  	document.getElementById('sign').classList.add('active');
  	document.getElementById('sign_in').classList.remove('active');
};

document.getElementById('sign_in').onclick = function() {
  	document.getElementById('sign').classList.remove('active');
  	document.getElementById('sign_in').classList.add('active');
};

/*=======================================================	Ефект Друкарської Машинки  =======================================*/
$(document).ready(function(){
	$.fn.animate_Text = function() {
		var string = this.text();
		return this.each(function(){
			var $this = $(this);
			$this.html(string.replace(/./g, '<span class="new">$&</span>'));
			$this.find('span.new').each(function(i, el){
				setTimeout(function(){ $(el).addClass('div_opacity'); }, 20 * i);
			});
		});
	};
	$('#example').show();
	$('#example').animate_Text();
});

/*========================================================= 	Слайдер    ===================================================*/
'use strict'; /*код тут обробляється в строгому режимі*/
var slideShow = (function () {
	return function (selector, config) {
		var
            _slider = document.querySelector(selector), // основний елемент блоку
            _sliderContainer = _slider.querySelector('.slider__items'), // контейнер для .slider-item
            _sliderItems = _slider.querySelectorAll('.slider__item'), // колекція .slider-item
            _sliderControls = _slider.querySelectorAll('.slider__control'), // елементи управління
            _currentPosition = 0, // позиція лівого активного елементу
            _transformValue = 0, // значення трансформації .slider_wrapper
            _transformStep = 100, // величина кроку (для трансформації)
            _itemsArray = [], // масив елементів
            _timerId,
            _indicatorItems,
            _indicatorIndex = 0,
            _indicatorIndexMax = _sliderItems.length - 1,
            _stepTouch = 50,
            _config = {
              	isAutoplay: false, // автоматична зміна слайдів
              	directionAutoplay: 'next', // напрямок зміни слайдів
              	delayAutoplay: 5000, // інтервал між автоматичною зміною слайдів
             	isPauseOnHover: true // чи встановлювати паузу при піднесенні курсору до слайдеру
            };

            // настройка конфігурації слайдера в залежності від отриманих ключів
            for (var key in config) {
            	if (key in _config) {
            		_config[key] = config[key];
            	}
            }

          	// наповнення масиву _itemsArray
          	for (var i = 0, length = _sliderItems.length; i < length; i++) {
          		_itemsArray.push({ item: _sliderItems[i], position: i, transform: 0 });
          	}

          	// змінна position містить методи за допомогою якої можна отримати мінімальний і максимальний індекс елемента, 
          	// а також відповідного цим індексом позицію
          	var position = {
          		getItemIndex: function (mode) {
          			var index = 0;
          			for (var i = 0, length = _itemsArray.length; i < length; i++) {
          				if ((_itemsArray[i].position < _itemsArray[index].position && mode === 'min') || (_itemsArray[i].position > _itemsArray[index].position && mode === 'max')) {
          					index = i;
          				}
          			}
          			return index;
          		},
          		getItemPosition: function (mode) {
          			return _itemsArray[position.getItemIndex(mode)].position;
          		}
          	};

          	// функція, що виконує зміну слайда в зазначеному напрямку
          	var _move = function (direction) {
          		var nextItem, currentIndicator = _indicatorIndex;
          		
          		if (direction === 'next') {
          			_currentPosition++;
          			
          			if (_currentPosition > position.getItemPosition('max')) {
          				nextItem = position.getItemIndex('min');
          				_itemsArray[nextItem].position = position.getItemPosition('max') + 1;
          				_itemsArray[nextItem].transform += _itemsArray.length * 100;
          				_itemsArray[nextItem].item.style.transform = 'translateX(' + _itemsArray[nextItem].transform + '%)';
          			}
          			
          			_transformValue -= _transformStep;
          			_indicatorIndex = _indicatorIndex + 1;
          			
          			if (_indicatorIndex > _indicatorIndexMax) {
          				_indicatorIndex = 0;
          			}
          		} 
          		else {
          			_currentPosition--;
          			
          			if (_currentPosition < position.getItemPosition('min')) {
          				nextItem = position.getItemIndex('max');
          				_itemsArray[nextItem].position = position.getItemPosition('min') - 1;
          				_itemsArray[nextItem].transform -= _itemsArray.length * 100;
          				_itemsArray[nextItem].item.style.transform = 'translateX(' + _itemsArray[nextItem].transform + '%)';
          			}
          			
          			_transformValue += _transformStep;
          			_indicatorIndex = _indicatorIndex - 1;
          			
          			if (_indicatorIndex < 0) {
          				_indicatorIndex = _indicatorIndexMax;
          			}
          		}
          		_sliderContainer.style.transform = 'translateX(' + _transformValue + '%)';
          		_indicatorItems[currentIndicator].classList.remove('active');
          		_indicatorItems[_indicatorIndex].classList.add('active');
          	};

          	// функція, що здійснює перехід до слайду по його порядковому номеру
          	var _moveTo = function (index) {
          		var i = 0, direction = (index > _indicatorIndex) ? 'next' : 'prev';
          		while (index !== _indicatorIndex && i <= _indicatorIndexMax) {
          			_move(direction);
          			i++;
          		}
          	};

          	// функція для запуску автоматичної зміни слайдів через проміжки часу
          	var _startAutoplay = function () {
          		if (!_config.isAutoplay) {
          			return;
          		}
          		_stopAutoplay();
          		
          		_timerId = setInterval(function () {
          			_move(_config.directionAutoplay);
          		}, _config.delayAutoplay);
          	};

          	// функція, яка відключає автоматичну зміну слайдів
          	var _stopAutoplay = function () {
          		clearInterval(_timerId);
          	};

          	// функція, що додає індикатори до слайдеру
          	var _addIndicators = function () {
          		var indicatorsContainer = document.createElement('ol');
          		indicatorsContainer.classList.add('slider__indicators');
          		for (var i = 0, length = _sliderItems.length; i < length; i++) {
          			var sliderIndicatorsItem = document.createElement('li');
          			if (i === 0) {
          				sliderIndicatorsItem.classList.add('active');
          			}
          			sliderIndicatorsItem.setAttribute("data-slide-to", i);
          			indicatorsContainer.appendChild(sliderIndicatorsItem);
          		}
          		_slider.appendChild(indicatorsContainer);
          		_indicatorItems = _slider.querySelectorAll('.slider__indicators > li')
          	};

          	var _isTouchDevice = function () {
          		return !!('ontouchstart' in window || navigator.maxTouchPoints);
          	};

          	// функція, що здійснює установку оброблювачів для подій
          	var _setUpListeners = function () {
          		var _startX = 0;
          		if (_isTouchDevice()) {
          			_slider.addEventListener('touchstart', function (e) {
          				_startX = e.changedTouches[0].clientX;
          				_startAutoplay();
          			});
          			
          			_slider.addEventListener('touchend', function (e) {
          				var
          				_endX = e.changedTouches[0].clientX,
          				_deltaX = _endX - _startX;
          				if (_deltaX > _stepTouch) {
          					_move('prev');
          				} 
          				else if (_deltaX < -_stepTouch) {
          					_move('next');
          				}
          				_startAutoplay();
          			});
          		} 
          		else {
          			for (var i = 0, length = _sliderControls.length; i < length; i++) {
          				_sliderControls[i].classList.add('slider__control_show');
          			}
          		}
          		
          		_slider.addEventListener('click', function (e) {
          			if (e.target.classList.contains('slider__control')) {
          				e.preventDefault();
          				_move(e.target.classList.contains('slider__control_next') ? 'next' : 'prev');
          				_startAutoplay();
          			} 
          			else if (e.target.getAttribute('data-slide-to')) {
          				e.preventDefault();
          				_moveTo(parseInt(e.target.getAttribute('data-slide-to')));
          				_startAutoplay();
          			}
          		});
          		document.addEventListener('visibilitychange', function () {
          			if (document.visibilityState === "hidden") {
          				_stopAutoplay();
          			} 
          			else {
          				_startAutoplay();
          			}
          		}, false);
          	
          		if (_config.isPauseOnHover && _config.isAutoplay) {
          			_slider.addEventListener('mouseenter', function () {
          				_stopAutoplay();
          			});
          			_slider.addEventListener('mouseleave', function () {
          				_startAutoplay();
          			});
          		}
          	};

          	// додаємо індикатори до слайдеру
          	_addIndicators();
          	// встановлюємо обробники для подій
          	_setUpListeners();
          	// запускаємо автоматичну зміну слайдів, якщо встановлений відповідний ключ
          	_startAutoplay();

          	return {
            	// метод слайдера для переходу до наступного слайду
            	next: function () {
            		_move('next');
            	},
            	// метод слайдера для переходу до попереднього слайду          
            	left: function () {
            		_move('prev');
            	},
            	// метод відключає автоматичну зміну слайдів
            	stop: function () {
            		_config.isAutoplay = false;
            		_stopAutoplay();
            	},
            	// метод запускає автоматичну зміну слайдів
            	cycle: function () {
            		_config.isAutoplay = true;
            		_startAutoplay();
            	}
        	}
    	}
}());

slideShow('.slider', {
	isAutoplay: true
});

/*====================================================== 	ANIMATION    ===================================================*/ 
new WOW().init();