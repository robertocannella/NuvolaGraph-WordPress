
import ListGroup from "./components/ListGroup";
import DailyListGroup from "./components/DailyListGroup";



const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']

const App = () => {


    return (
            <div className="grid grid-cols-2 gap-6 p-3 mt-4 mr-4 w-4/5">
                <div className="shadow bg-white rounded-lg p-3">
                    <DailyListGroup heading={"General Availability"} days={days}/>
                </div>
                <div className="shadow bg-white rounded-lg p-3">
                    <DailyListGroup days={days} heading={"Exceptions"}/>
                </div>

            </div>

    );
};
export default App;
export const tuesday_data = [

    {
        "day_of_week": "Tuesday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    },
    {
        "day_of_week": "Tuesday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    },
    {
        "day_of_week": "Tuesday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    }
]

export const data = [
    {
        "day_of_week": "Monday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    },
    {
        "day_of_week": "Monday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "12:00:00",
        "end_time": "1:00:00"
    },
    {
        "day_of_week": "Tuesday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    },
    {
        "day_of_week": "Wednesday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    },
    {
        "day_of_week": "Thursday",
        "start_date": "2023-07-01",
        "end_date": "2023-11-30",
        "start_time": "08:00:00",
        "end_time": "10:00:00"
    }
]