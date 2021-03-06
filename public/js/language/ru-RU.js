var language = {
	error_noview: 'Календарь: Шаблон вида {0} не найден.',
	error_dateformat: 'Календарь: Не верный формат даты {0}. Должне быть или "now" или "yyyy-mm-dd"',
	error_loadurl: 'Календарь: Не назначен УРЛ для загрузки событий.',
	error_where: 'Календарь: Не правильная навигация {0}. Можно только "next", "prev" или "today"',

	title_year: 'Год {0}',
	title_month: '{0} год {1}',
	title_week: '{0} неделя года {1}',
	title_day: '{0} {1} {2} год {3}',

	week:'Неделя',

	m0: 'Январь',
	m1: 'Февраль',
	m2: 'Март',
	m3: 'Апрель',
	m4: 'Май',
	m5: 'Июнь',
	m6: 'Июль',
	m7: 'Август',
	m8: 'Сентябть',
	m9: 'Октябрь',
	m10: 'Ноябрь',
	m11: 'Декабрь',

    ms0: 'Янв',
    ms1: 'Фев',
    ms2: 'Мар',
    ms3: 'Апр',
    ms4: 'Май',
    ms5: 'Июн',
    ms6: 'Июл',
    ms7: 'Авг',
    ms8: 'Сен',
    ms9: 'Окт',
    ms10: 'Ноя',
    ms11: 'Дек',

	d0: 'Воскресение',
	d1: 'Понедельник',
	d2: 'Вторник',
	d3: 'Среда',
	d4: 'Четверг',
	d5: 'Пятница',
	d6: 'Суббота',

	easter: 'Пасха',
	easterMonday: 'Пасхальный понедельник'
};

if(!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}