import $ from 'jquery';
import moment from 'moment';
import helpers from './helpers'
import Calendar from 'tui-calendar';
import HttpClient from './HttpClient'

const CALENDAR_TYPE_LABEL = {
  day: 'Day',
  week: 'Week',
  month: 'Month'
}

class TodoList {
  constructor() {
    this.calendarLabelWrapper = $('#changeViewBtn');
    this.calendarActionWrapper = $('#actionBtn');
    this.createTodoPopup = $('#createNewTodo');
    this.workDatetimePicker = $('#workDatetime');

    this.rootElement = '#calendar';
    this.calendarOpts = {
      defaultView: helpers.getCalendarDefaultView(),
      taskView: true
    };
  }

  async init() {
    await this.createCalendar();
    this.setCalendarTypeLabel(helpers.getCalendarDefaultView())
    this.loadWorks();
    this.initForm();

    $('#createNewTodoBtn').on('click', (e) => {
      this.handleClickNewTodoBtn(e)
    })

    this.calendar.on('clickSchedule', (e) => {
      this.handleClickSchedule(e)
    })

    this.calendarLabelWrapper.on('click', 'a.dropdown-item', (e) => {
      this.handleChangeViewEvent(e)
    })
    this.calendarActionWrapper.on('click', 'button.btn', (e) => {
      this.handleActionEvent(e)
    })
    this.workDatetimePicker.on('apply.daterangepicker', (e, picker) => {
      this.handleWorkDatetimePicker(picker)
    })

    this.createTodoPopup.find('button.btn-submit').on('click', () => {
      this.createTodoPopup.find('form').submit();
    })
    this.createTodoPopup.find('form').on('submit', (e) => {
      this.handleSubmitTodoForm(e)
    })

    this.createTodoPopup.find('button.btn-delete').on('click', (e) => {
      this.handleDeleteTodo(e)
    })
  }

  initForm() {
    this.createTodoPopup.find('.alert').hide();
    this.createTodoPopup.find('button.btn-delete').hide();
    $('#workName').val('');
    $('#workStatus').val(0);
    // Set default start - end
    this.setTodoDateTime(moment().set({minute: 0, second: 0}).format('YYYY-MM-DD HH:mm:ss'),
      moment().set({hour: 23, minute: 59, second: 0}).format('YYYY-MM-DD HH:mm:ss'))
  }

  async loadWorks() {
    try {
      const { data } = await HttpClient.get('works');
      this.calendar.createSchedules(this.buildDataSchedules(data));
    } catch (e) {
      alert(e.msg)
    }
  }

  handleClickNewTodoBtn(e) {
    this.initForm()
    this.createTodoPopup.modal('show');
    this.createTodoPopup.removeAttr('data-id');
  }

  async handleClickSchedule({ schedule }) {
    try {
      const { data } = await HttpClient.get(`works/${schedule.id}`);

      this.createTodoPopup.find('.alert').hide();
      $('#workName').val(data.work_name);
      $('#workStatus').val(data.status);

      this.setTodoDateTime(data.start_at, data.end_at);

      this.createTodoPopup.attr('data-id', schedule.id);
      this.createTodoPopup.modal('show');
      this.createTodoPopup.find('button.btn-delete').show();
    } catch (e) {
      alert(e.msg)
    }
  }

  handleChangeViewEvent(e) {
    e.preventDefault();
    const calendarType = $(e.target).data('type');
    this.setCalendarView(calendarType)
  }

  handleActionEvent(e) {
    e.preventDefault();
    const action = $(e.target).data('type');
    switch (action) {
      case 'prev':
        this.calendar.prev();
        break;
      case 'next':
        this.calendar.next();
        break
      default:
        this.calendar.today();
        break;
    }
  }

  handleWorkDatetimePicker(picker) {
    $('#workStartAt').val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'))
    $('#workEndAt').val(picker.endDate.format('YYYY-MM-DD HH:mm:ss'))
  }

  async handleSubmitTodoForm(e) {
    e.preventDefault();
    const form = $(e.target);
    this.createTodoPopup.find('button.btn-submit').prop('disabled', true);
    this.createTodoPopup.find('.btn-submit .spinner-border').show();

    let formAction = 'works/new';
    const todoId = this.createTodoPopup.attr('data-id')
    if (todoId) {
      formAction = `works/${todoId}/update`
    }

    try {
      const { data } = await HttpClient.post(formAction, form.serialize())
      if (todoId) {
        // Update
        const start = moment(data.start_at).format()
        const end = moment(data.end_at).format()
        this.calendar.updateSchedule(todoId, 1, {
          title: data.work_name,
          bgColor: this.getColorEvent(data.status),
          borderColor: this.getColorEvent(data.status),
          start,
          end
        });
      } else {
        //Add new
        this.calendar.createSchedules(this.buildDataSchedules([data]));
      }
      this.createTodoPopup.modal('hide');
    } catch (e) {
      this.createTodoPopup.find('.alert').text(e.msg).show();
    }
    this.createTodoPopup.find('button.btn-submit').prop('disabled', false);
    this.createTodoPopup.find('.btn-submit .spinner-border').hide();
  }

  async handleDeleteTodo(e) {
    $(e.target).find('.spinner-border').show();
    const todoId = this.createTodoPopup.attr('data-id');
    if (!todoId) return false;

    try {
      await HttpClient.get(`works/${todoId}/delete`);
      this.calendar.deleteSchedule(todoId, 1);
      this.createTodoPopup.modal('hide');
    } catch (e) {
      alert(e.msg);
    }
    $(e.target).find('.spinner-border').hide();
  }

  createCalendar() {
    this.calendar = new Calendar(this.rootElement, this.calendarOpts);
  }

  getCalendar() {
    return this.calendar;
  }

  buildDataSchedules(data) {
    return data.map((work) => {
      const start = moment(work.start_at).format()
      const end = moment(work.end_at).format()
      return {
        id: work.id,
        calendarId: 1,
        title: work.work_name,
        category: 'time',
        dueDateClass: '',
        color: '#fff',
        bgColor: this.getColorEvent(work.status),
        borderColor: this.getColorEvent(work.status),
        start,
        end
      }
    })
  }

  setCalendarView(type) {
    helpers.setCalendarDefaultView(type);
    this.setCalendarTypeLabel(type);
    this.calendar.changeView(type, true);
  }

  getTypeLabel(key) {
    return CALENDAR_TYPE_LABEL[key];
  }

  setCalendarTypeLabel(type) {
    this.calendarLabelWrapper.find('button.btn').text(this.getTypeLabel(type))
  }

  setTodoDateTime(start, end) {
    this.workDatetimePicker.data('daterangepicker').setStartDate(moment(start));
    this.workDatetimePicker.data('daterangepicker').setEndDate(moment(end));
    $('#workStartAt').val(moment(start).format('YYYY-MM-DD HH:mm:ss'))
    $('#workEndAt').val(moment(end).format('YYYY-MM-DD HH:mm:ss'))
  }

  getColorEvent(status) {
    let color;
    switch (status) {
      case '1':
        color = '#9d9d9d';
        break;
      case '2':
        color = '#ffbb3b';
        break;
      case '3':
        color = '#03bd9e';
        break;
      default:
        color = '#00a9ff';
        break;
    }

    return color;
  }
}

export default new TodoList()
