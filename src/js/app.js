import $ from 'jquery';
import 'bootstrap';
import TodoList from './TodoList';
import './DatetimePicker'

TodoList.init();

$('input.input-date').daterangepicker({
  timePicker: true,
  timePicker24Hour: true,
  locale: {
    format: 'HH:mm DD-MM-YYYY'
  }
});
