const CALENDAR_DEFAULT_VIEW_KEY = 'calendar_default_view'

export default {
  getCalendarDefaultView: () => {
    return localStorage.getItem(CALENDAR_DEFAULT_VIEW_KEY) || 'month';
  },

  setCalendarDefaultView: (value) => {
    return localStorage.setItem(CALENDAR_DEFAULT_VIEW_KEY, value);
  }
}